<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\CategoryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\Category
 *
 * @property int $id
 * @property string|null $mpath
 * @property string $title
 * @property string|null $status
 * @property string|null $status_inherited
 * @property int|null $order
 * @property string|null $ext_id
 * @property string|null $name
 * @property string|null $titles_mode
 * @property string|null $kits_show
 * @property int|null $parent_id
 * @property int|null $left_key
 * @property int|null $right_key
 * @property int|null $level
 * @property string|null $sections_list
 * @property string|null $href
 * @property string|null $rz_mpath
 * @property bool|null $allow_index_three_parameters
 * @property string|null $on_subdomain
 * @property string|null $oversized
 * @property bool|null $is_subdomain
 * @property bool|null $disable_kit_ratio
 * @property bool|null $is_rozetka_top
 * @property int $is_deleted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read Collection|CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Category active()
 * @method static CategoryFactory factory(...$parameters)
 * @method static Builder|Category loadTranslations() WARNING! This scope must be in start of all query
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereAllowIndexThreeParameters($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDisableKitRatio($value)
 * @method static Builder|Category whereExtId($value)
 * @method static Builder|Category whereHref($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereIsDeleted($value)
 * @method static Builder|Category whereIsRozetkaTop($value)
 * @method static Builder|Category whereIsSubdomain($value)
 * @method static Builder|Category whereKitsShow($value)
 * @method static Builder|Category whereLeftKey($value)
 * @method static Builder|Category whereLevel($value)
 * @method static Builder|Category whereMpath($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereOnSubdomain($value)
 * @method static Builder|Category whereOrder($value)
 * @method static Builder|Category whereOversized($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereRightKey($value)
 * @method static Builder|Category whereRzMpath($value)
 * @method static Builder|Category whereSectionsList($value)
 * @method static Builder|Category whereStatus($value)
 * @method static Builder|Category whereStatusInherited($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereTitlesMode($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'mpath',
        'title',
        'status',
        'status_inherited',
        'order',
        'ext_id',
        'name',
        'titles_mode',
        'kits_show',
        'parent_id',
        'left_key',
        'right_key',
        'level',
        'sections_list',
        'href',
        'rz_mpath',
        'allow_index_three_parameters',
        'on_subdomain',
        'oversized',
        'is_subdomain',
        'disable_kit_ratio',
        'is_rozetka_top',
        'is_deleted',
        'status',
        'status_inherited',
    ];

    protected $casts = [
        'title' => Translatable::class,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id')->withDefault();
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * @param static|Builder $query
     */
    public function scopeActive($query): Builder
    {
        return $query
            ->where('status', '!=', 'locked')
            ->where('status_inherited', '!=', 'locked');
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
     * @param int $id
     * @return Category|null
     */
    public static function getById(int $id): ?Category
    {
        return static::where('id', $id)
            ->active()
            ->first();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public static function getByIds(array $ids, array $fields = []): array
    {
        $query = static::whereIn('id', $ids)
            ->with('translations')
            ->orderBy('order')
            ->active();

        if ($fields) {
            $query->select($fields);
        }

        return $query
            ->get()
            ->toArray();
    }

    /**
     * @param Category $category
     * @return Collection|array<Category>
     */
    public static function getChildCategories(Category $category): Collection
    {
        return static::select(['*'])
            ->where('left_key', '>', $category->left_key)
            ->where('right_key', '<', $category->right_key)
            ->selectTranslation('title')
            ->orderByDesc('level')
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->active()
            ->get();
    }

    public function getNestedCategoriesIds(int $categoryId): array
    {
        return DB::table($this->getTable() . ' as c')
            ->join($this->getTable() . ' as childs', function ($join) {
                $join->on('c.left_key', '<=', 'childs.left_key')
                    ->on('c.right_key', '>=', 'childs.right_key');
            })->select('childs.id')->where(['c.id' => $categoryId])->pluck('id')->toArray();
    }


    public function getMainCategoriesIds(): array
    {
        return static::where('parent_id', '=' , 0)
            ->where('level', '=' , 1)
            ->where('left_key', '!=' , 0)
            ->where('right_key', '!=' , 0)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @param Category $category
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function getParentsQuery(Category $category): Builder
    {
        return static::query()
            ->select(['id'])
            ->from(self::getModel()->getTable(), 'c')
            ->where('left_key', '<=', $category->left_key)
            ->where('right_key', '>=', $category->right_key)
            ->where('id', '>', 0);
    }
}
