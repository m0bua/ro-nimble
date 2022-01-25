<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Eloquent\GoodsOptionNumber
 *
 * @property int $id
 * @property int $goods_id
 * @property int $option_id
 * @property float $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Eloquent\Goods $goods
 * @property-read \App\Models\Eloquent\Option $option
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionNumber whereValue($value)
 * @mixin \Eloquent
 */
class GoodsOptionNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'goods_id',
        'option_id',
        'value',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class)->withDefault();
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withDefault();
    }
}
