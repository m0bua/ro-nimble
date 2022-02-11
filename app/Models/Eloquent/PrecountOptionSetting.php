<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


/**
 * App\Models\Eloquent\PrecountOptionSetting
 *
 * @property int $option_id
 * @property int $category_id
 * @property int $options_settings_id
 * @property int $is_deleted
 * @method static Builder|PrecountOptionSetting newModelQuery()
 * @method static Builder|PrecountOptionSetting newQuery()
 * @method static Builder|PrecountOptionSetting query()
 * @method static Builder|PrecountOptionSetting whereCategoryId($value)
 * @method static Builder|PrecountOptionSetting whereIsDeleted($value)
 * @method static Builder|PrecountOptionSetting whereOptionId($value)
 * @method static Builder|PrecountOptionSetting whereOptionsSettingsId($value)
 * @mixin Eloquent
 */
class PrecountOptionSetting extends Model
{
    use HasFactory;
    use HasFillable;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'option_id',
        'category_id',
        'options_settings_id',
        'is_deleted'
    ];

    private OptionSetting $optionSettings;
    private Category $category;
    private Option $option;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->optionSettings = app()->make(OptionSetting::class);
        $this->category = app()->make(Category::class);
        $this->option = app()->make(Option::class);
    }

    public function fillTable(int $categoryId): void
    {
        $optionSettingsTable = $this->optionSettings->getTable();
        $precountOptionSettingsTable = $this->getTable();
        $categoryTable = $this->category->getTable();
        $optTable = $this->option->getTable();
        $specificOptions = array_merge($this->option->getSpecificOptions(), [26294, 218618]);
        $specificOptions = implode(',', $specificOptions);
        static::query()->update(['is_deleted' => 1]);

        $childCategories = $this->category->getNestedCategoriesIds($categoryId);
        $childCategories = join(',', $childCategories);
        $whereClause = "childs.id IN($childCategories)";

        $upsertQuery = <<<UPSERT_QUERY
            WITH inherited_settings AS(
                SELECT
                    DISTINCT ON (o.id, childs.id)
                    os.id AS options_settings_id,
                    o.id  AS option_id,
                    childs.id AS category_id
                FROM "$optionSettingsTable" os
                    JOIN "$optTable" o ON
                        os.option_id = o.id
                 JOIN "$categoryTable" os_category ON
                      (os.category_id IS NULL AND o.category_id = os_category.id) OR
                      (os.category_id IS NOT NULL AND os.category_id = os_category.id)
                  JOIN "$categoryTable" childs ON
                      os_category.left_key <= childs.left_key AND childs.right_key <= os_category.right_key AND childs.level > 0
              WHERE {$whereClause} AND (o.category_id != 0 OR o.id IN({$specificOptions}))
              ORDER BY
                  o.id DESC,
                  childs.id DESC,
                  os_category.level DESC,
                  os.category_id IS NULL ASC,
                  os.id DESC
            )
            INSERT INTO "$precountOptionSettingsTable" AS pos(
                options_settings_id,
                option_id,
                category_id,
                is_deleted
            ) SELECT
                options_settings_id,
                option_id,
                category_id,
                0
            FROM inherited_settings
            ON CONFLICT (option_id,category_id) DO UPDATE
            SET options_settings_id = EXCLUDED.options_settings_id, is_deleted = EXCLUDED.is_deleted
            WHERE pos.options_settings_id != EXCLUDED.options_settings_id OR pos.is_deleted != EXCLUDED.is_deleted;
        UPSERT_QUERY;

        DB::insert($upsertQuery);
        DB::table($precountOptionSettingsTable)->where(['is_deleted' => 1])->delete();
    }
}
