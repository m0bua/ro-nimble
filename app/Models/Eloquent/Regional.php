<?php

namespace App\Models\Eloquent;

use App\Helpers\CountryHelper;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\Regional
 *
 * @property int $id
 * @property int|null $foreign_key
 * @property string $country
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Model $entity
 * @method static Builder|Regional withCountry()
 * @method static Builder|Regional newModelQuery()
 * @method static Builder|Regional newQuery()
 * @method static Builder|Regional query()
 * @method static Builder|Regional whereColumn($value)
 * @method static Builder|Regional whereCreatedAt($value)
 * @method static Builder|Regional whereId($value)
 * @method static Builder|Regional whereCountry($value)
 * @method static Builder|Regional whereUpdatedAt($value)
 * @method static Builder|Regional whereValue($value)
 * @mixin Eloquent
 */
abstract class Regional extends Model
{
    use HasFactory;

    /**
     * Custom model namespace
     *
     * @var string|null
     */
    protected ?string $regionalModelNamespace = null;

    /**
     * Defines entity that Regional belongs to
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        if ($this->regionalModelNamespace) {
            $model = $this->regionalModelNamespace;
        } else {
            $model = Str::replaceLast('Regional', '', static::class);
        }

        return $this->belongsTo($model);
    }

    /**
     * @param static|Builder $query
     */
    public function scopeWithCountry($query): Builder
    {
        return $query->whereIn('country', [
            CountryHelper::getRequestCountry()
        ]);
    }
}
