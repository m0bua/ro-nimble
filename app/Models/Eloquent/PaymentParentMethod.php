<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Eloquent\PaymentParentMethod
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PaymentParentMethodTranslation[] $translations
 * @property-read int|null $translations_count
 * @property string $title title translation
 * @method static Builder|PaymentParentMethod loadTranslations() WARNING! This scope must be in start of all query
 * @method static Builder|PaymentParentMethod newModelQuery()
 * @method static Builder|PaymentParentMethod newQuery()
 * @method static Builder|PaymentParentMethod query()
 * @method static Builder|PaymentParentMethod whereCreatedAt($value)
 * @method static Builder|PaymentParentMethod whereId($value)
 * @method static Builder|PaymentParentMethod whereName($value)
 * @method static Builder|PaymentParentMethod whereOrder($value)
 * @method static Builder|PaymentParentMethod whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentParentMethod extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'order',
        'title',
    ];

    protected $casts = [
        'title' => Translatable::class,
    ];

    /**
     * @param array $names
     * @return Collection
     */
    public static function getIdsByNames(array $names): Collection
    {
        return static::query()
            ->whereIn('name', $names)
            ->pluck('id');
    }

    /**
     * Get payment methods with translations for filters builder
     *
     * @param array $ids
     * @return Collection
     */
    public static function getForFilters(array $ids): Collection
    {
        return static::query()
            ->select()
            ->whereIn('id', $ids)
            ->selectTranslation('title')
            ->orderBy('order')
            ->get()
            ->toBase();
    }
}
