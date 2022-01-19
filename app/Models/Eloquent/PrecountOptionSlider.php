<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Eloquent\PrecountOptionSlider
 *
 * @property int $option_id
 * @property int $category_id
 * @property float $max_value
 * @property float $min_value
 * @property string $comparable
 * @property int $is_deleted
 * @method static Builder|PrecountOptionSlider newModelQuery()
 * @method static Builder|PrecountOptionSlider newQuery()
 * @method static Builder|PrecountOptionSlider query()
 * @method static Builder|PrecountOptionSlider whereCategoryId($value)
 * @method static Builder|PrecountOptionSlider whereComparable($value)
 * @method static Builder|PrecountOptionSlider whereIsDeleted($value)
 * @method static Builder|PrecountOptionSlider whereMaxValue($value)
 * @method static Builder|PrecountOptionSlider whereMinValue($value)
 * @method static Builder|PrecountOptionSlider whereOptionId($value)
 * @mixin Eloquent
 */
class PrecountOptionSlider extends Model
{
    use HasFactory;
    use HasFillable;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'option_id',
        'category_id',
        'max_value',
        'min_value',
        'comparable',
        'is_deleted'
    ];

    private OptionSetting $optionSettings;
    private Category $category;
    private GoodsOptionNumber $goodsOption;
    private Goods $goods;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->goodsOption = app()->make(GoodsOptionNumber::class);
        $this->goods = app()->make(Goods::class);
        $this->optionSettings = app()->make(OptionSetting::class);
    }

    public function fillTable(): void
    {
        static::query()->update(['is_deleted' => 1]);
        $goodsOptionsTable = $this->goodsOption->getTable();
        $goodsTable = $this->goods->getTable();
        $precountOptionSlidersTable = $this->getTable();
        $optionSettingsTable = $this->optionSettings->getTable();

        $upsertQuery = "
            WITH sliders AS (
                SELECT
                    go.option_id,
                    g.category_id,
                    cast(max(go.value) as DOUBLE PRECISION),
                    cast(min(go.value) as DOUBLE PRECISION),
                    os.comparable
                FROM $goodsOptionsTable go
                    JOIN $goodsTable g ON
                        g.id=go.goods_id
                    JOIN $optionSettingsTable os ON
                        os.option_id=go.option_id
                        AND (os.comparable NOT IN ('disable','locked'))
                GROUP BY
                    g.category_id
                    ,go.option_id
                    ,os.comparable
                ORDER BY g.category_id DESC
            )
            INSERT INTO $precountOptionSlidersTable
                SELECT sliders.*, 0 FROM sliders
            ON CONFLICT (option_id,category_id)
            DO UPDATE SET
                max_value=EXCLUDED.max_value,
                min_value=EXCLUDED.min_value,
                comparable=EXCLUDED.comparable,
                is_deleted=EXCLUDED.is_deleted;
        ";

        DB::select($upsertQuery);
        DB::table($precountOptionSlidersTable)->where(['is_deleted' => 1])->delete();
    }
}
