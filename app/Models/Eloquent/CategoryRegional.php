<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryRegional
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $country
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Category $entity
 * @method static Builder|CategoryRegional newModelQuery()
 * @method static Builder|CategoryRegional newQuery()
 * @method static Builder|CategoryRegional query()
 * @method static Builder|CategoryRegional whereCategoryId($value)
 * @method static Builder|CategoryRegional whereColumn($value)
 * @method static Builder|CategoryRegional whereCreatedAt($value)
 * @method static Builder|CategoryRegional whereId($value)
 * @method static Builder|CategoryRegional whereCountry($value)
 * @method static Builder|CategoryRegional whereUpdatedAt($value)
 * @method static Builder|CategoryRegional whereValue($value)
 * @mixin Eloquent
 */
class CategoryRegional extends Regional
{
    protected $fillable = [
        'category_id',
        'country',
        'column',
        'value',
    ];
}
