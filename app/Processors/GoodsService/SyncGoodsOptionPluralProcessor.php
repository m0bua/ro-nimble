<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IndexRefill;

class SyncGoodsOptionPluralProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected GoodsOptionPlural $model;
    public static array $uniqueBy = ['goods_id', 'option_id', 'value_id'];

    /**
     * ChangeGoodsOptionPluralProcessor constructor.
     * @param GoodsOptionPlural $model
     */
    public function __construct(GoodsOptionPlural $model)
    {
        $this->model = $model;
    }

    /**
     * Update or create entity
     *
     * @param array<string>|string $uniqueBy
     * @param array<string>|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $data['needs_index'] = 1;
        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        Artisan::call(IndexRefill::class, ['--goods-ids' => $this->data['goods_id']]);
    }
}
