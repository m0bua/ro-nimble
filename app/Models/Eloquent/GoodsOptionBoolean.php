<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\GoodsOptionBoolean
 *
 * @property int $id
 * @property int $goods_id
 * @property int $option_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $need_delete
 * @property-read \App\Models\Eloquent\Goods $goods
 * @property-read \App\Models\Eloquent\Option $option
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsOptionBoolean whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsOptionBoolean extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'goods_id',
        'option_id',
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
