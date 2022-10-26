<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\OptionValueRelation
 *
 * @property int $id
 * @property int $value_id
 * @property int $option_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @method static Builder|OptionValueRelation newModelQuery()
 * @method static Builder|OptionValueRelation newQuery()
 * @method static Builder|OptionValueRelation query()
 * @method static Builder|OptionValueRelation whereCreatedAt($value)
 * @method static Builder|OptionValueRelation whereId($value)
 * @method static Builder|OptionValueRelation whereOptionId($value)
 * @method static Builder|OptionValueRelation whereUpdatedAt($value)
 * @method static Builder|OptionValueRelation whereValueId($value)
 * @mixin Eloquent
 */
class OptionValueRelation extends Model
{
    use HasFactory;
    use HasFillable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'value_id',
        'option_id',
    ];
}
