<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Series
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $category_id
 * @property int|null $producer_id
 * @property string|null $ext_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Series newModelQuery()
 * @method static Builder|Series newQuery()
 * @method static Builder|Series query()
 * @method static Builder|Series whereCategoryId($value)
 * @method static Builder|Series whereCreatedAt($value)
 * @method static Builder|Series whereExtId($value)
 * @method static Builder|Series whereId($value)
 * @method static Builder|Series whereName($value)
 * @method static Builder|Series whereProducerId($value)
 * @method static Builder|Series whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Series extends Model
{
    use HasFactory;
    use HasFillable;


    protected $fillable = [
        'id',
        'name',
        'category_id',
        'producer_id',
        'ext_id',
    ];
}
