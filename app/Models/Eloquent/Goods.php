<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\GoodsFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Goods
 *
 * @property int $id
 * @property array<string> $title
 * @property string|null $name
 * @property int|null $category_id
 * @property string|null $mpath
 * @property float|null $price
 * @property float|null $rank
 * @property string|null $sell_status
 * @property int|null $producer_id
 * @property int|null $seller_id
 * @property int|null $group_id
 * @property int|null $is_group_primary
 * @property string|null $status_inherited
 * @property int|null $order
 * @property int|null $series_id
 * @property string|null $state
 * @property int $needs_index
 * @property int $is_deleted
 * @property string|null $country_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Bonus|null $bonus
 * @property-read Category|null $category
 * @property-read int|null $goods_options_count
 * @property-read Collection|OptionValue[] $optionValues
 * @property-read int|null $option_values_count
 * @property-read Collection|Option[] $options
 * @property-read int|null $options_count
 * @property-read Collection|PaymentMethod[] $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read Producer|null $producer
 * @property-read Collection|PromotionGoodsConstructor[] $promotionGoodsConstructors
 * @property-read int|null $promotion_goods_constructors_count
 * @property-read Collection|PromotionGroupConstructor[] $promotionGroupConstructors
 * @property-read int|null $promotion_group_constructors_count
 * @property-read Collection|GoodsTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read Collection|Label[] $label
 * @method static GoodsFactory factory(...$parameters)
 * @method static Builder|Goods newModelQuery()
 * @method static Builder|Goods newQuery()
 * @method static Builder|Goods query()
 * @method static Builder|Goods whereCategoryId($value)
 * @method static Builder|Goods whereCountryCode($value)
 * @method static Builder|Goods whereCreatedAt($value)
 * @method static Builder|Goods whereGroupId($value)
 * @method static Builder|Goods whereId($value)
 * @method static Builder|Goods whereIsDeleted($value)
 * @method static Builder|Goods whereIsGroupPrimary($value)
 * @method static Builder|Goods whereMpath($value)
 * @method static Builder|Goods whereName($value)
 * @method static Builder|Goods whereNeedsIndex($value)
 * @method static Builder|Goods whereOrder($value)
 * @method static Builder|Goods wherePrice($value)
 * @method static Builder|Goods whereProducerId($value)
 * @method static Builder|Goods whereRank($value)
 * @method static Builder|Goods whereSellStatus($value)
 * @method static Builder|Goods whereSellerId($value)
 * @method static Builder|Goods whereSeriesId($value)
 * @method static Builder|Goods whereState($value)
 * @method static Builder|Goods whereStatusInherited($value)
 * @method static Builder|Goods whereTitle($value)
 * @method static Builder|Goods whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Goods extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public const SELL_STATUS_ARCHIVE = 'archive';
    public const SELL_STATUS_HIDDEN = 'hidden';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'title',
        'category_id',
        'mpath',
        'price',
        'rank',
        'sell_status',
        'producer_id',
        'seller_id',
        'merchant_id',
        'group_id',
        'is_group_primary',
        'status_inherited',
        'order',
        'country_code',
        'series_id',
        'state',
        'needs_index',
        'is_deleted',
    ];

    protected $casts = [
        'title' => Translatable::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class)->withDefault();
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'goods_payment_method');
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'goods_options_plural');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class, 'goods_options_plural', 'goods_id', 'value_id');
    }

    public function promotionGoodsConstructors(): HasMany
    {
        return $this->hasMany(PromotionGoodsConstructor::class);
    }

    public function promotionGroupConstructors(): HasMany
    {
        return $this->hasMany(PromotionGroupConstructor::class, 'group_id', 'group_id');
    }

    public function bonus(): HasOne
    {
        return $this->hasOne(Bonus::class, 'goods_id');
    }

    public function scopeMarkedAsDeleted(Builder $builder): Builder
    {
        return $builder->where('is_deleted', 1);
    }

    /**
     * Get goods with loaded bonus and payment methods
     *
     * @param array $ids
     * @return Collection
     */
    public static function findManyWithBonusAndPayments(array $ids): Collection
    {
        return static::query()
            ->whereIn('id', $ids)
            ->with([
                'bonus',
                'paymentMethods' => fn($q) => $q->active(),
            ])
            ->get();
    }

    public function label(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);

    }
}
