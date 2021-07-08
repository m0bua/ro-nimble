<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryTranslation
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $entity
 * @method static Builder|CategoryTranslation newModelQuery()
 * @method static Builder|CategoryTranslation newQuery()
 * @method static Builder|CategoryTranslation query()
 * @method static Builder|CategoryTranslation whereCategoryId($value)
 * @method static Builder|CategoryTranslation whereColumn($value)
 * @method static Builder|CategoryTranslation whereCreatedAt($value)
 * @method static Builder|CategoryTranslation whereId($value)
 * @method static Builder|CategoryTranslation whereLang($value)
 * @method static Builder|CategoryTranslation whereUpdatedAt($value)
 * @method static Builder|CategoryTranslation whereValue($value)
 * @mixin Eloquent
 */
class CategoryTranslation extends Translation
{
    protected $fillable = [
        'category_id',
        'lang',
        'column',
        'value',
    ];
}
