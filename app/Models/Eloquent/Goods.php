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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Eloquent\Goods
 *
 * @property int $id
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $country_code
 * @property-read Category|null $category
 * @property-read Collection|GoodsOption[] $goodsOptions
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
 * @property array<string> $title title translations
 * @method static GoodsFactory factory(...$parameters)
 * @method static Builder|Goods markedAsDeleted()
 * @method static Builder|Goods needsIndex()
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
 * @method static Builder|Goods whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Goods extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

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
        return $this->belongsToMany(PaymentMethod::class);
    }

    public function goodsOptions(): HasMany
    {
        return $this->hasMany(GoodsOption::class);
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

    public function scopeMarkedAsDeleted(Builder $builder): Builder
    {
        return $builder->where('is_deleted', 1);
    }

    public function scopeNeedsIndex(Builder $builder): Builder
    {
        return $builder->where('needs_index', 1);
    }

    public function getGoodsDetails(array $goodsIds): array
    {
        $goodsIdsStr = implode(',', $goodsIds);
        return static::query()->select(
            [
                'goods.id as id',
                //TODO: delete this string when will be one bonus for one goods in DB
                DB::raw("(SELECT jsonb_build_object(
                                 'comment_bonus_charge', b.comment_bonus_charge,
                                 'comment_photo_bonus_charge', b.comment_photo_bonus_charge,
                                 'comment_video_bonus_charge', b.comment_video_bonus_charge,
                                 'bonus_not_allowed_pcs', b.bonus_not_allowed_pcs,
                                 'comment_video_child_bonus_charge', b.comment_video_child_bonus_charge,
                                 'bonus_charge_pcs', b.bonus_charge_pcs,
                                 'use_instant_bonus', b.use_instant_bonus,
                                 'premium_bonus_charge_pcs', b.premium_bonus_charge_pcs)
                          FROM bonuses as b
                          WHERE b.goods_id in ($goodsIdsStr)
                          order by b.updated_at DESC
                          limit 1) as bonuses"
                ),
                //TODO: uncomment this string when will be one bonus for one goods in DB
//                DB::raw("jsonb_agg(
//                distinct
//                    jsonb_build_object(
//                        'comment_bonus_charge', b.comment_bonus_charge,
//                        'comment_photo_bonus_charge', b.comment_photo_bonus_charge,
//                        'comment_video_bonus_charge', b.comment_video_bonus_charge,
//                        'bonus_not_allowed_pcs', b.bonus_not_allowed_pcs,
//                        'comment_video_child_bonus_charge', b.comment_video_child_bonus_charge,
//                        'bonus_charge_pcs',         bonuses.bonus_charge_pcs,
//                        'use_instant_bonus',        bonuses.use_instant_bonus,
//                        'premium_bonus_charge_pcs', bonuses.premium_bonus_charge_pcs))
//                filter (where bonuses.goods_id is not null)
//                AS bonuses"),
                DB::raw("jsonb_agg(distinct
                    jsonb_build_object(
                        'id',        pm.id,
                        'parent_id', pm.parent_id,
                        'name',      pm.name,
                        'order',     pm.order,
                        'status',    pm.status
                    ))
                filter (where gpm.goods_id is not null and pm.id is not null)
                AS payment_methods"
                )
            ]
        )
            ->from('goods')
            //TODO: uncomment this string when will be one bonus for one goods in DB
//            ->leftJoin('bonuses', 'bonuses.goods_id', '=', 'goods.id')
            ->leftJoin('goods_payment_method as gpm', 'gpm.goods_id', '=', 'goods.id')
            ->leftJoin('payment_methods as pm',  function($join) {
                $join->on('gpm.payment_method_id', '=', 'pm.id')
                    ->where('pm.status', '=', 'active');
            })
            ->whereIn('goods.id', $goodsIds)
            ->groupBy('goods.id')
            ->get()
            ->toArray()
        ;
    }
}
