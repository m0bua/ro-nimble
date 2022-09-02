<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryOptionTranslation
 *
 * @property int $id
 * @property int|null $category_option_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property array|null $compound_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read CategoryOption $entity
 * @method static Builder|CategoryOptionTranslation newModelQuery()
 * @method static Builder|CategoryOptionTranslation newQuery()
 * @method static Builder|CategoryOptionTranslation query()
 * @method static Builder|CategoryOptionTranslation whereCategoryOptionId($value)
 * @method static Builder|CategoryOptionTranslation whereColumn($value)
 * @method static Builder|CategoryOptionTranslation whereCompoundKey($value)
 * @method static Builder|CategoryOptionTranslation whereCreatedAt($value)
 * @method static Builder|CategoryOptionTranslation whereId($value)
 * @method static Builder|CategoryOptionTranslation whereLang($value)
 * @method static Builder|CategoryOptionTranslation whereUpdatedAt($value)
 * @method static Builder|CategoryOptionTranslation whereValue($value)
 * @mixin Eloquent
 */
class CategoryOptionTranslation extends Translation
{
    protected $fillable = [
        'category_option_id',
        'lang',
        'column',
        'value',
        'compound_key',
    ];

    protected $casts = [
        'compound_key' => 'array',
    ];
}
