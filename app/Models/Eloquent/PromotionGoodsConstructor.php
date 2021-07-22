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
 * App\Models\Eloquent\PromotionGoodsConstructor
 *
 * @property int $id
 * @property int $constructor_id
 * @property int $goods_id
 * @property int $needs_index
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_migrate
 * @property-read Goods $goods
 * @method static Builder|PromotionGoodsConstructor newModelQuery()
 * @method static Builder|PromotionGoodsConstructor newQuery()
 * @method static Builder|PromotionGoodsConstructor query()
 * @method static Builder|PromotionGoodsConstructor whereConstructorId($value)
 * @method static Builder|PromotionGoodsConstructor whereCreatedAt($value)
 * @method static Builder|PromotionGoodsConstructor whereGoodsId($value)
 * @method static Builder|PromotionGoodsConstructor whereId($value)
 * @method static Builder|PromotionGoodsConstructor whereIsDeleted($value)
 * @method static Builder|PromotionGoodsConstructor whereNeedsIndex($value)
 * @method static Builder|PromotionGoodsConstructor whereNeedsMigrate($value)
 * @method static Builder|PromotionGoodsConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionGoodsConstructor extends Model
{
    use HasFactory;
    use HasFillable;


    protected $fillable = [
        'id',
        'constructor_id',
        'goods_id',
        'needs_index',
        'is_deleted',
        'needs_migrate',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }
}
