<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\OptionValueCategoryRelation
 *
 * @property int $id
 * @property int $category_id
 * @property int $value_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @method static Builder|OptionValueCategoryRelation newModelQuery()
 * @method static Builder|OptionValueCategoryRelation newQuery()
 * @method static Builder|OptionValueCategoryRelation query()
 * @method static Builder|OptionValueCategoryRelation whereCategoryId($value)
 * @method static Builder|OptionValueCategoryRelation whereCreatedAt($value)
 * @method static Builder|OptionValueCategoryRelation whereId($value)
 * @method static Builder|OptionValueCategoryRelation whereUpdatedAt($value)
 * @method static Builder|OptionValueCategoryRelation whereValueId($value)
 * @mixin Eloquent
 */
class OptionValueCategoryRelation extends Model
{
    use HasFactory;
    use HasFillable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'category_id',
        'value_id',
    ];
}
