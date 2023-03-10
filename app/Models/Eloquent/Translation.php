<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\Translation
 *
 * @property int $id
 * @property int|null $foreign_key
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Model $entity
 * @method static Builder|Translation withLang()
 * @method static Builder|Translation newModelQuery()
 * @method static Builder|Translation newQuery()
 * @method static Builder|Translation query()
 * @method static Builder|Translation whereColumn($value)
 * @method static Builder|Translation whereCreatedAt($value)
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

    /**
     * @param static|Builder $query
     */
    public function scopeWithLang($query): Builder
    {
        return $query->whereIn('lang', [
            App::getLocale(), config('translatable.default_language')
        ]);
    }
}
