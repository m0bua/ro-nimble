<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\GoodsCarInfo
 *
 * @property int $id
 * @property int $goods_id
 * @property int $car_trim_id
 * @property int $car_brand_id
 * @property int $car_model_id
 * @property int $car_year_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @method static Builder|GoodsCarInfo newModelQuery()
 * @method static Builder|GoodsCarInfo newQuery()
 * @method static Builder|GoodsCarInfo query()
 * @method static Builder|GoodsCarInfo whereCarBrandId($value)
 * @method static Builder|GoodsCarInfo whereCarModelId($value)
 * @method static Builder|GoodsCarInfo whereCarTrimId($value)
 * @method static Builder|GoodsCarInfo whereCarYearId($value)
 * @method static Builder|GoodsCarInfo whereCreatedAt($value)
 * @method static Builder|GoodsCarInfo whereGoodsId($value)
 * @method static Builder|GoodsCarInfo whereId($value)
 * @method static Builder|GoodsCarInfo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GoodsCarInfo extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'goods_id',
        'car_trim_id',
        'car_brand_id',
        'car_model_id',
        'car_year_id',
    ];
}
