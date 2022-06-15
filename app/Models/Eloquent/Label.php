<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Label
 *
 * @property int $id
 * @property string $name
 * @property string $status
 * @property int $order
 * @property string $date_start
 * @property string $date_end
 * @property string $color
 * @property string $color_front
 * @property string $country_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @method static Builder|Label newModelQuery()
 * @method static Builder|Label newQuery()
 * @method static Builder|Label query()
 * @method static Builder|Label whereColor($value)
 * @method static Builder|Label whereColorFront($value)
 * @method static Builder|Label whereCountryCode($value)
 * @method static Builder|Label whereCreatedAt($value)
 * @method static Builder|Label whereDateEnd($value)
 * @method static Builder|Label whereDateStart($value)
 * @method static Builder|Label whereId($value)
 * @method static Builder|Label whereName($value)
 * @method static Builder|Label whereOrder($value)
 * @method static Builder|Label whereStatus($value)
 * @method static Builder|Label whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Label extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    const STATUS_ACTIVE = 'active';
    const STATUS_LOCKED = 'locked';

    protected $fillable = [
        'id',
        'name',
        'status',
        'order',
        'date_start',
        'date_end',
        'color',
        'color_front',
        'country_code',
    ];

    protected $casts = [
        'title' => Translatable::class,
        'text' => Translatable::class,
    ];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class);
    }
}
