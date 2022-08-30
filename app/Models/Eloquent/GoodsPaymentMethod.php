<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Eloquent\GoodsPaymentMethod
 *
 * @property int $id
 * @property int $goods_id
 * @property int $payment_method_id
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsPaymentMethod query()
 * @mixin \Eloquent
 */
class GoodsPaymentMethod extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'goods_payment_method';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'goods_id',
        'payment_method_id',
    ];
}
