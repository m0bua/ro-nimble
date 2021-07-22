<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\PromotionGroupConstructor
 *
 * @property int $id
 * @property int $constructor_id
 * @property int $group_id
 * @property int $needs_index
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_migrate
 * @method static Builder|PromotionGroupConstructor newModelQuery()
 * @method static Builder|PromotionGroupConstructor newQuery()
 * @method static Builder|PromotionGroupConstructor query()
 * @method static Builder|PromotionGroupConstructor whereConstructorId($value)
 * @method static Builder|PromotionGroupConstructor whereCreatedAt($value)
 * @method static Builder|PromotionGroupConstructor whereGroupId($value)
 * @method static Builder|PromotionGroupConstructor whereId($value)
 * @method static Builder|PromotionGroupConstructor whereIsDeleted($value)
 * @method static Builder|PromotionGroupConstructor whereNeedsIndex($value)
 * @method static Builder|PromotionGroupConstructor whereNeedsMigrate($value)
 * @method static Builder|PromotionGroupConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionGroupConstructor extends Model
{
    use HasFactory;
    use HasFillable;


    protected $table = 'promotion_groups_constructors';

    protected $fillable = [
        'id',
        'constructor_id',
        'group_id',
        'needs_migrate',
        'needs_index',
        'is_deleted',
    ];
}
