<?php

namespace App\Processors\GoodsService\OptionSetting;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionValue;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * Maximum count goods for one bulk indexing
     */
    protected int $maxBatch;

    /**
     * @var GoodsBuffer
     */
    protected GoodsBuffer $goodsBuffer;

    /**
     * @param OptionSetting $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(OptionSetting $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inerhitDoc
     *
     * @param $uniqueBy
     * @param array|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();

        $oldData = OptionSetting::select(['os.status', 'os.comparable', 'o.type', 'op.state as parent_state'])
            ->from(OptionSetting::getModel()->getTable(), 'os')
            ->join(Option::getModel()->getTable() . ' as o', 'o.id', 'os.option_id')
            ->leftJoin(Option::getModel()->getTable() . ' as op', 'o.parent_id', 'op.id')
            ->where('os.option_id', '=', $data['option_id'])
            ->where('os.category_id', '=', $data['category_id'])
            ->first();

        $this->model->upsert($data, $uniqueBy, $update);
        // saving translations after creating record if we can do that
        $this->saveTranslations();

        $statusVariants = [
            /** не обновляються */
            [
                0b0000, // Нічого не змінилось
                0b0001, // Варіант не розглядався (case8_11 tests/functional/sync/gs/GSCest.php)
                0b0010, // если comparable not in(bottom, main), и это не менялось в этом сообщении,то не отправляем ничего
                0b0101, // Нічого не змінилось
                0b0110, // если было: status=locked, comparable=main, а в сообщении: status=active, comparable=disable,
                // то опция как не отдавалась, так и не отдается и считается заблокированной, ничего не изменилось
                // и отправлять товары на обновление не нужно
                0b1000, // если comparable not in(bottom, main), и это не менялось в этом сообщении,то не отправляем ничего
                0b1001, // если было: status=active, comparable=disable, а в сообщении: status=locked, comparable=bottom
                // то тоже ничего в редис писать не нужно
                0b1010, // Нічого не змінилось
                0b1111, // Нічого не змінилось
            ],
            /** обновляються */
            [
                0b0011, // Варіант не розглядався
                0b0100, // Варіант не розглядався
                0b0111, // Варіант не розглядався (case8_7 tests/functional/sync/gs/GSCest.php)
                0b1011, // если было status=active, comparable=disable, а в сообщении: status=active, comparable=main,
                // то такую запись начнем отдавать по апи, потому нужно по ней переотправить товары
                0b1100, // Варіант не розглядався
                0b1101, // Варіант не розглядався
                0b1110, // если было: status=active, comparable=main, а в сообщении: status=active, comparable=disable ,
                // то такую опцию отдавать не будем больше, и потому нужно отправить ее товары на обновление
            ]
        ];
        $opStatus = 0b0000;
        if (\in_array($data['status'], OptionSetting::$availableStatuses)) $opStatus |= 0b1000;
        if (\in_array($data['comparable'], ['bottom', 'main'])) $opStatus |= 0b0100;
        if (\in_array($oldData['status'], OptionSetting::$availableStatuses)) $opStatus |= 0b0010;
        if (\in_array($oldData['comparable'], ['bottom', 'main'])) $opStatus |= 0b0001;
        if (Option::STATE_LOCKED === $oldData['parent_state']) {
            return true;
        }
        if (
            \in_array($opStatus, $statusVariants[1])
        ) {
            $query = $this->model->query();
            switch ($oldData['type']) {
                case Option::TYPE_CHECKBOX:
                    $query = $query->from('goods_option_booleans as mt');
                    break;
                case Option::TYPE_INTEGER:
                case Option::TYPE_DECIMAL:
                    $query = $query->from('goods_option_numbers as mt');
                    break;
                default:
                    $query = $query->from('goods_options_plural as mt')
                        ->join('option_values as ov', 'ov.id', 'mt.value_id')
                        ->whereNotIn('ov.status', [OptionValue::STATUS_LOCKED, OptionValue::STATUS_NOT_USE]);
            }

            $query->select(['mt.goods_id'])->distinct()
                ->join('goods as g', 'g.id', 'mt.goods_id')
                ->where('mt.option_id', '=', $data['option_id'])
                ->whereIn('g.status', [Goods::STATUS_ACTIVE, Goods::STATUS_CONFIGURABLE_BY_SERVICES])
                ->whereNotIn('g.sell_status', [Goods::SELL_STATUS_HIDDEN, Goods::SELL_STATUS_ARCHIVE])
                ->whereIn('g.status_inherited', [Goods::STATUS_INHERITED_ACTIVE]);

            if (0 !== $data['category_id']) {
                $query->where('g.category_id', '=', $data['category_id']);
            }

            foreach ($query->trueCursor($this->maxBatch) as $goods) {
                $this->goodsBuffer->radd($goods->pluck('goods_id')->toArray());
            }
        }

        return true;
    }
}
