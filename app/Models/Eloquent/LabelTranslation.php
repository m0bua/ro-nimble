<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\LabelTranslation
 *
 * @property int $id
 * @property int $label_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Label $entity
 * @method static Builder|LabelTranslation newModelQuery()
 * @method static Builder|LabelTranslation newQuery()
 * @method static Builder|LabelTranslation query()
 * @method static Builder|LabelTranslation whereColumn($value)
 * @method static Builder|LabelTranslation whereCreatedAt($value)
 * @method static Builder|LabelTranslation whereId($value)
 * @method static Builder|LabelTranslation whereLabelId($value)
 * @method static Builder|LabelTranslation whereLang($value)
 * @method static Builder|LabelTranslation whereUpdatedAt($value)
 * @method static Builder|LabelTranslation whereValue($value)
 * @mixin Eloquent
 */
class LabelTranslation extends Translation
{
    protected $fillable = [
        'label_id',
        'lang',
        'column',
        'value',
    ];
}
