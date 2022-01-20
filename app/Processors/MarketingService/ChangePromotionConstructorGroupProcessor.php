<?php

namespace App\Processors\MarketingService;

use App\Console\Commands\IndexRefill;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;
use Illuminate\Support\Facades\Artisan;

class ChangePromotionConstructorGroupProcessor extends AbstractProcessor
{
    use WithUpsert;

    const CONSTRUCTOR_ID_KEY = 'constructor_id';
    const GROUP_ID_KEY = 'group_id';

    public static array $uniqueBy = [
        self::CONSTRUCTOR_ID_KEY,
        self::GROUP_ID_KEY,
    ];

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        self::GROUP_ID_KEY,
    ];

    protected static array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
    ];

    protected PromotionGroupConstructor $model;

    /**
     * ChangePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
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
        $data['needs_migrate'] = 1;
        $data['is_deleted'] = 0;

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }

    protected function afterProcess(): void
    {
        $goods = Goods::query()
            ->select('id')
            ->where('group_id', '=', $this->data['group_id'])
            ->get();

        IndexGoods::query()->insertOrIgnore($goods->toArray());
    }
}
