<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Comments
 *
 * @property int $goods_id
 * @property int $goods_group_id
 * @property int $countMarks
 * @property int $summMarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GoodsComments newModelQuery()
 * @method static Builder|GoodsComments newQuery()
 * @method static Builder|GoodsComments query()
 * @method static Builder|GoodsComments whereCountMarks($value)
 * @method static Builder|GoodsComments whereCreatedAt($value)
 * @method static Builder|GoodsComments whereGoodsGroupId($value)
 * @method static Builder|GoodsComments whereGoodsId($value)
 * @method static Builder|GoodsComments whereSummMarks($value)
 * @method static Builder|GoodsComments whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GoodsComments extends Model
{
    use HasFillable;

    public $incrementing = false;

    protected $fillable = [
        'goods_id',
        'goods_group_id',
        'count_marks',
        'summ_marks'
    ];
}
