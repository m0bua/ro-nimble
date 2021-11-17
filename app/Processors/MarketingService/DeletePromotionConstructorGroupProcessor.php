<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;
use Illuminate\Support\Arr;

class DeletePromotionConstructorGroupProcessor extends AbstractProcessor
{
    use WithDelete;

    const CONSTRUCTOR_ID_KEY = 'constructor_id';
    const GROUP_ID_KEY = 'group_id';

    public static bool $softDelete = true;

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
     * DeletePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
    {
        $this->model = $model;
    }

    public static function updatableFields(): array
    {
        return ['is_deleted' => 1, 'needs_index' => 0, 'needs_migrate' => 0];
    }
}
