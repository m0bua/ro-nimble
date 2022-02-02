<?php

namespace App\Console\Commands;

use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use Bschmitt\Amqp\Amqp;
use Illuminate\Console\Command;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Message\AMQPMessage;

class IndexingConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:index {queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer for indexing goods data';

    /**
     * @var string
     */
    protected string $indexName;

    /**
     * @var Elastic
     */
    protected Elastic $goodsElastic;

    /**
     * @var Collection
     */
    protected Collection $goodsIds;

    /**
     * @var array
     */
    protected array $indexBody = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GoodsModel $goodsElastic)
    {
        $this->goodsElastic = $goodsElastic;

        parent::__construct();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function handle(): int
    {
        $queue = $this->argument('queue');

        (new Amqp())->consume($queue, function (AMQPMessage $message) {
            $bodyMsg = json_decode($message->getBody(), true);
            $this->indexName = $bodyMsg['index_name'];
            $this->goodsIds = collect($bodyMsg['ids']);
            $this->resetIndexBody();
            $this->prepareIndexBody();
            $this->goodsElastic->bulk($this->indexBody);

            $message->ack();
        }, config('amqp.properties.local'));

        return 0;
    }

    /**
     * @return void
     */
    private function resetIndexBody()
    {
        $this->indexBody = ['body' => []];
    }

    /**
     * @return void
     */
    private function prepareIndexBody()
    {
        $goods = $this->getGoods();
        $optionsChecked = $this->getOptionsChecked()->keyBy('id');
        $optionsPlural = $this->getOptionsPlural()->keyBy('goods_id');
        $optionSliders = $this->getOptionSliders()->keyBy('goods_id');
        $goodsPromotions = $this->getGoodsPromotions()->keyBy('id');
        $groupsPromotions = $this->getGroupsPromotions()->keyBy('id');
        $paymentMethods = $this->getPaymentMethods()->keyBy('id');
        $bonuses = $this->getBonuses()->keyBy('id');
        $carInfos = $this->getCarsInfo()->keyBy('id');
        $producerTitles = $this->getProducerTitles()->keyBy('id');

        $goods->map(function ($item) use (
            $optionsChecked,
            $optionsPlural,
            $optionSliders,
            $goodsPromotions,
            $groupsPromotions,
            $paymentMethods,
            $bonuses,
            $carInfos,
            $producerTitles
        ) {
            $ocItem = $optionsChecked->get($item->id);
            $opItem = $optionsPlural->get($item->id);
            $osItem = $optionSliders->get($item->id);
            $gpItem = $goodsPromotions->get($item->id);
            $grpItem = $groupsPromotions->get($item->id);
            $pmItem = $paymentMethods->get($item->id);
            $bItem = $bonuses->get($item->id);
            $ciItem = $carInfos->get($item->id);
            $ptItem = $producerTitles->get($item->id);

            $item->group_token = $item->group_id ? "g{$item->group_id}" : "p{$item->id}";
            $item->categories_path = collect(json_decode($item->categories_path))
                ->map(fn($i) => (int)$i)->toArray();
            $item->option_checked = $ocItem ? json_decode($ocItem->option_checked) : [];
            $item->options = $opItem ? json_decode($opItem->options) : [];
            $item->option_values = $opItem ? json_decode($opItem->option_values) : [];
            $item->option_sliders = $osItem ? json_decode($osItem->option_sliders) : [];
            $item->promotion_ids = $this->mergePromotionItems($gpItem, $grpItem);
            $item->payment_method_ids = $pmItem ? json_decode($pmItem->payment_method_ids) : [];
            $item->bonus_charge_pcs = $bItem ? $bItem->bonus_charge_pcs : 0;
            $item->tags = json_decode($item->tags);
            $item->car_trim_id = [];
            $item->car_brand_id = [];
            $item->car_model_id = [];
            $item->car_year_id = [];
            $item->producer_title = $ptItem ? $ptItem->producer_title : '';

            if (!empty($ciItem) && !empty($ciItem->car_infos)) {
                $data = collect(json_decode($ciItem->car_infos));
                $item->car_trim_id = $data->pluck('car_trim_id')->unique();
                $item->car_brand_id = $data->pluck('car_brand_id')->unique();
                $item->car_model_id = $data->pluck('car_model_id')->unique();
                $item->car_year_id = $data->pluck('car_year_id')->unique();
            }

            $this->indexBody['body'][] = [
                'index' => [
                    '_index' => $this->indexName,
                    '_id' => $item->id,
                ],
            ];

            $this->indexBody['body'][] = $item;
        });
    }

    /**
     * @param object|null $goodsItem
     * @param object|null $groupItem
     * @return array
     */
    private function mergePromotionItems(?object $goodsItem, ?object $groupItem): array
    {
        return collect(array_merge(
            $goodsItem ? json_decode($goodsItem->promotion_id) : [],
            $groupItem ? json_decode($groupItem->promotion_id) : []
        ))->unique()->toArray();
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    private function getGoods(): Collection
    {
        return DB::table('goods')
            ->select([
                'id',
                'category_id',
                'group_id',
                'is_group_primary',
                'price',
                'producer_id',
                'seller_id',
                DB::raw("(CASE
                        WHEN merchant_id = 1 THEN 1
                        WHEN merchant_id = 2 THEN 1
                        WHEN merchant_id = 43 THEN 2
                        WHEN merchant_id = 0 THEN 0
                        ELSE 3
                    END) AS merchant_type"
                ),
                'series_id',
                'sell_status',
                'state',
                'status_inherited',
                'country_code',
                'rank',
                DB::raw("to_json(string_to_array(trim('.' FROM mpath), '.')) AS categories_path"),
                DB::raw("'[]'::JSON as tags"),
            ])
            ->whereIn('id', $this->goodsIds)
            ->get();
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    private function getProducerTitles(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(pt.value, '') as producer_title"),
            ])
            ->join('producer_translations as pt', 'g.producer_id', '=','pt.producer_id')
            ->whereIn('g.id', $this->goodsIds)
            ->where('pt.lang', '=','ru')
            ->get();
    }

    /**
     * @param array $goodsIds
     * @return \Illuminate\Support\Collection
     */
    private function getOptionsChecked(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(DISTINCT o.id) filter (WHERE o.id IS NOT NULL), '[]') as option_checked")
            ])
            ->join('goods_option_booleans AS gob', 'g.id', '=', 'gob.goods_id')
            ->join('options AS o', 'gob.option_id', '=', 'o.id')
            ->where('o.state', '=', 'active')
            ->whereIn('o.option_record_comparable', ['main', 'bottom'])
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }

    /**
     * @param array $goodsIds
     * @return Collection
     */
    private function getOptionsPlural(): Collection
    {
        return DB::table('goods_options_plural', 'gop')
            ->select([
                'gop.goods_id',
                DB::raw("coalesce(json_agg(DISTINCT o.id) filter (WHERE o.id IS NOT NULL), '[]') as options"),
                DB::raw("coalesce(json_agg(DISTINCT gop.value_id) filter (WHERE ov.status IS NOT NULL), '[]') AS option_values"),
            ])
            ->join('options as o', 'gop.option_id', '=', 'o.id')
            ->join('option_values as ov', 'gop.value_id', '=', 'ov.id')
            ->where('o.state', '=', 'active')
            ->where('ov.status', '=', 'active')
            ->whereIn('o.option_record_comparable', [
                'main',
                'bottom'
            ])
            ->whereIn('o.type', [
                'List',
                'ComboBox',
                'ListValues',
                'CheckBoxGroup',
                'CheckBoxGroupValues',
            ])
            ->whereIn('gop.goods_id', $this->goodsIds)
            ->groupBy('gop.goods_id')
            ->get();
    }

    /**
     * @return Collection
     */
    private function getOptionSliders(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id as goods_id',
                DB::raw("coalesce(json_agg(DISTINCT jsonb_build_object('id', o.id, 'value', gon.value)) filter (WHERE gon.value IS NOT NULL), '[]') AS option_sliders"),
            ])
            ->join('goods_option_numbers as gon', 'g.id', '=', 'gon.goods_id')
            ->join('options as o', 'gon.option_id', '=', 'o.id')
            ->where('o.state', '=', 'active')
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }

    /**
     * @return Collection
     */
    private function getGoodsPromotions(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(DISTINCT pc.promotion_id), '[]') as promotion_id"),
            ])
            ->join('promotion_goods_constructors as pgc', 'pgc.goods_id','=', 'g.id')
            ->join('promotion_constructors as pc', 'pc.id', '=', 'pgc.constructor_id')
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }

    /**
     * @return Collection
     */
    private function getGroupsPromotions(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(DISTINCT pc.promotion_id), '[]') as promotion_id"),
            ])
            ->join('promotion_groups_constructors as pgrc', 'pgrc.group_id', '=', 'g.group_id')
            ->join('promotion_constructors as pc', 'pc.id', '=', 'pgrc.constructor_id')
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }

    /**
     * @return Collection
     */
    private function getPaymentMethods(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(DISTINCT pm.id), '[]') as payment_method_ids")
            ])
            ->join('goods_payment_method as gpm', 'g.id', '=', 'gpm.goods_id')
            ->join('payment_methods as pm', 'gpm.payment_method_id', '=', 'pm.id')
            ->where('pm.status', '=', 'active')
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }

    /**
     * @return Collection
     */
    private function getBonuses(): Collection
    {
        return DB::table('goods', 'g')
            ->select([
                DB::raw('DISTINCT g.id'),
                'b.bonus_charge_pcs'
            ])
            ->join('bonuses as b', 'g.id', '=', 'b.goods_id')
            ->whereIn('g.id', $this->goodsIds)
            ->get();
    }

    /**
     * @return Collection
     */
    private function getCarsInfo(): Collection
    {
        return Db::table('goods', 'g')
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(DISTINCT jsonb_build_object(
                'car_trim_id', gci.car_trim_id,
                'car_brand_id', gci.car_brand_id,
                'car_model_id', gci.car_model_id,
                'car_year_id', gci.car_year_id)), '[]') AS car_infos"),
            ])
            ->join('goods_car_infos as gci', 'g.id', '=', 'gci.goods_id')
            ->whereIn('g.id', $this->goodsIds)
            ->groupBy('g.id')
            ->get();
    }
}
