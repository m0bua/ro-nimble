<?php

namespace App\Models\Eloquent;

use App\Casts\Regional;
use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;
use App\Traits\Eloquent\HasRegionals;

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
 * @property int $need_delete
 * @property-read Collection|SeriesTranslation[] $translations
 * @property-read int|null $translations_count
 * @property array<string> $title title translations
 * @method static SeriesFactory factory(...$parameters)
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
    use HasTranslations;
    use HasRegionals;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'category_id',
        'producer_id',
        'ext_id',
    ];

    protected $casts = [
        'title' => Translatable::class,
        'name' => Regional::class,
    ];

    /**
     * @param array $names
     * @return array
     */
    public static function getIdsByNames(array $names): array
    {
        return static::whereIn('name', $names)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public static function getSeriesForFilters(array $ids)
    {
        return static::whereIn('id', $ids)
            ->with('translations')
            ->get();
    }
}
