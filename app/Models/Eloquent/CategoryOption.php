<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\CategoryOptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Category|null $category
 * @property-read Option|null $option
 * @property-read Collection|CategoryOptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static CategoryOptionFactory factory(...$parameters)
 * @method static Builder|CategoryOption loadTranslations() WARNING! This scope must be in start of all query
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
    use HasTranslations;

    protected $fillable = [
        'id',
        'category_id',
        'option_id',
        'value',
    ];

    protected $casts = [
        'value' => Translatable::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withDefault();
    }

    /**
     * @param int $categoryId
     * @param int $optionId
     * @return mixed
     */
    public static function getCategoryOption(int $categoryId, int $optionId): ?CategoryOption
    {
        return static::where('category_id', $categoryId)
            ->where('option_id', $optionId)
            ->first();
    }
}
