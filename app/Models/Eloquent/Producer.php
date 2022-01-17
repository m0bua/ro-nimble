<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\ProducerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Producer
 *
 * @property int $id
 * @property int|null $order_for_promotion
 * @property int|null $producer_rank
 * @property string|null $name
 * @property string $title
 * @property string|null $title_rus
 * @property string|null $ext_id
 * @property string|null $text
 * @property string|null $status
 * @property string|null $attachments
 * @property bool|null $show_background
 * @property bool|null $show_logo
 * @property bool|null $disable_filter_series
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_index
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @property-read Collection|ProducerTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Producer active()
 * @method static ProducerFactory factory(...$parameters)
 * @method static Builder|Producer loadTranslations() WARNING! This scope must be in start of all query
 * @method static Builder|Producer needsIndex()
 * @method static Builder|Producer newModelQuery()
 * @method static Builder|Producer newQuery()
 * @method static Builder|Producer query()
 * @method static Builder|Producer whereAttachments($value)
 * @method static Builder|Producer whereCreatedAt($value)
 * @method static Builder|Producer whereDisableFilterSeries($value)
 * @method static Builder|Producer whereExtId($value)
 * @method static Builder|Producer whereId($value)
 * @method static Builder|Producer whereIsDeleted($value)
 * @method static Builder|Producer whereName($value)
 * @method static Builder|Producer whereNeedsIndex($value)
 * @method static Builder|Producer whereOrderForPromotion($value)
 * @method static Builder|Producer whereProducerRank($value)
 * @method static Builder|Producer whereShowBackground($value)
 * @method static Builder|Producer whereShowLogo($value)
 * @method static Builder|Producer whereStatus($value)
 * @method static Builder|Producer whereText($value)
 * @method static Builder|Producer whereTitle($value)
 * @method static Builder|Producer whereTitleRus($value)
 * @method static Builder|Producer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Producer extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'order_for_promotion',
        'producer_rank',
        'name',
        'title',
        'ext_id',
        'text',
        'status',
        'attachments',
        'show_background',
        'show_logo',
        'disable_filter_series',
        'is_deleted',
        'needs_index',
    ];

    protected $casts = [
        'title' => Translatable::class,
    ];

    /**
     * @return HasMany
     */
    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNeedsIndex(Builder $builder): Builder
    {
        return $builder->where('needs_index', 1);
    }

    /**
     * @param static|Builder $query
     */
    public function scopeActive($query)
    {
        return $query
            ->where('status', '!=', 'locked');
    }

    /**
     * @param array $names
     * @return array
     */
    public static function getIdsByNames(array $names): array
    {
        return static::whereIn('name', $names)
            ->active()
            ->pluck('id')
            ->toArray();
    }

    /**
     * @param array $ids
     * @return array
     */
    public static function getActiveByIds(array $ids): array
    {
        return static::whereIn('id', $ids)
            ->active()
            ->pluck('id')
            ->toArray();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public static function getProducersForFilters(array $ids)
    {
        return static::whereIn('id', $ids)
            ->with('translations')
            ->active()
            ->get();
    }
}
