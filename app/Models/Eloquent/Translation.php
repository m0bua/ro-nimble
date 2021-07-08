<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
 * @property-read Model $entity
 * @method static Builder|Translation newModelQuery()
 * @method static Builder|Translation newQuery()
 * @method static Builder|Translation query()
 * @method static Builder|Translation whereColumn($value)
 * @method static Builder|Translation whereCreatedAt($value)
 * @method static Builder|Translation whereGoodsId($value)
 * @method static Builder|Translation whereId($value)
 * @method static Builder|Translation whereLang($value)
 * @method static Builder|Translation whereUpdatedAt($value)
 * @method static Builder|Translation whereValue($value)
 * @mixin Eloquent
 */
abstract class Translation extends Model
{
    use HasFactory;

    /**
     * Custom model namespace
     *
     * @var string|null
     */
    protected ?string $translatedModelNamespace = null;

    /**
     * Defines entity that translation belongs to
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        if ($this->translatedModelNamespace) {
            $model = $this->translatedModelNamespace;
        } else {
            $model = Str::replaceLast('Translation', '', static::class);
        }

        return $this->belongsTo($model);
    }
}
