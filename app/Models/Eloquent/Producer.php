<?php

namespace App\Models\Eloquent;

use App\Casts\Regional;
use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\ProducerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Eloquent\AbstractModel as Model;
use App\Traits\Eloquent\HasRegionals;

/**
 * App\Models\Eloquent\Producer
 *
 * @property int $id
 * @property int|null $order_for_promotion
 * @property int|null $producer_rank
 * @property string|null $name
 * @property string $title
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
 * @property int $need_delete
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @method static Builder|Producer active()
 * @method static ProducerFactory factory(...$parameters)
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
    use HasRegionals;

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
        'name' => Regional::class,
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
     * @param SupportCollection $category
     * @return Collection
     */
    public function getProducersForFilters(array $ids, SupportCollection $category): SupportCollection
    {
        $producerTable = $this->getTable();
        $producerAttachmentTable = ProducersAttachment::make()->getTable();
        $filterAutorankingTable = FilterAutoranking::make()->getTable();

        $query = static::query()
            ->select([
                'p.id as id',
                'p.name',
                'p.title',
                'pa.url as image',
            ])
            ->leftJoin("{$producerAttachmentTable} as pa", function (JoinClause $join) {
                $join->on('pa.producer_id', 'p.id')
                ->where('pa.variant', 'original')
                ->where('pa.group_name', 'images');
            })
            ->from($producerTable, 'p')
            ->whereIn('p.id', $ids)
            ->active();

        // на акциях категория отсутствует
        if ($category->isNotEmpty()) {
            $query
                ->addSelect([
                    DB::raw('coalesce(fa.is_value_show::int, 0) as is_value_show'),
                    DB::raw('coalesce(fa.parent_id::int, 0) as is_autoranking'),
                ])
                ->leftJoin("{$filterAutorankingTable} as fa", function(JoinClause $join) use ($category) {
                    $join->on('p.name', 'fa.filter_value')
                        ->where('fa.parent_id', "{$category->first()}")
                        ->where('fa.filter_name', 'producer');
                    })
                ->orderByDesc('is_value_show');
        } else {
            $query
                ->addSelect([
                    DB::raw('0 as is_value_show'),
                    DB::raw('0 as is_autoranking')
                ]);
        }

        return $query->get()->recursive();
    }

    /**
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ProducersAttachments::class);
    }
}
