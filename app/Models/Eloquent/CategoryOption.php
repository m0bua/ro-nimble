<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryOption
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $option_id
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category|null $category
 * @property-read Option|null $option
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
