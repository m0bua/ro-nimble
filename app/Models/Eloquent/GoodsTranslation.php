<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\GoodsTranslation
 *
 * @property int $id
 * @property int|null $goods_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Goods $entity
 * @method static Builder|GoodsTranslation newModelQuery()
 * @method static Builder|GoodsTranslation newQuery()
 * @method static Builder|GoodsTranslation query()
 * @method static Builder|GoodsTranslation whereColumn($value)
 * @method static Builder|GoodsTranslation whereCreatedAt($value)
 * @method static Builder|GoodsTranslation whereGoodsId($value)
 * @method static Builder|GoodsTranslation whereId($value)
 * @method static Builder|GoodsTranslation whereLang($value)
 * @method static Builder|GoodsTranslation whereUpdatedAt($value)
 * @method static Builder|GoodsTranslation whereValue($value)
 * @mixin Eloquent
 */
class GoodsTranslation extends Translation
{
    protected $fillable = [
        'goods_id',
        'lang',
        'column',
        'value',
    ];
}
