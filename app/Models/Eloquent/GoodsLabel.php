<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Eloquent\GoodsLabel
 *
 * @property int $goods_id
 * @property int $label_id
 * @property int $country_code
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsLabel query()
 * @mixin \Eloquent
 */
class GoodsLabel extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'goods_label';

    /**
     * @var string[]
     */
    protected $fillable = [
        'goods_id',
        'label_id',
        'country_code',
    ];
}
