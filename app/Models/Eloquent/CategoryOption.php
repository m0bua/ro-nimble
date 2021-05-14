<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryOption
 *
 * @property int $id
 * @property int $category_id
 * @property int $option_id
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CategoryOption newModelQuery()
 * @method static Builder|CategoryOption newQuery()
 * @method static Builder|CategoryOption query()
 * @method static Builder|CategoryOption whereCategoryId($value)
 * @method static Builder|CategoryOption whereCreatedAt($value)
 * @method static Builder|CategoryOption whereId($value)
 * @method static Builder|CategoryOption whereOptionId($value)
 * @method static Builder|CategoryOption whereUpdatedAt($value)
 * @method static Builder|CategoryOption whereValue($value)
 * @mixin Eloquent
 */
class CategoryOption extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'id',
        'category_id',
        'option_id',
        'value',
    ];
}
