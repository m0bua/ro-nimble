<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\Comments
 *
 * @property int $goods_id
 * @property int $goods_group_id
 * @property int $count_marks
 * @property int $summ_marks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @method static Builder|GoodsComment newModelQuery()
 * @method static Builder|GoodsComment newQuery()
 * @method static Builder|GoodsComment query()
 * @method static Builder|GoodsComment whereCountMarks($value)
 * @method static Builder|GoodsComment whereCreatedAt($value)
 * @method static Builder|GoodsComment whereGoodsGroupId($value)
 * @method static Builder|GoodsComment whereGoodsId($value)
 * @method static Builder|GoodsComment whereSummMarks($value)
 * @method static Builder|GoodsComment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GoodsComment extends Model
{
    use HasFillable;

    public $incrementing = false;

    protected $primaryKey = 'goods_id';

    protected $fillable = [
        'goods_id',
        'goods_group_id',
        'count_marks',
        'summ_marks'
    ];
}
