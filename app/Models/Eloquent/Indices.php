<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Eloquent\Indices
 *
 * @property string $name
 * @property string|null $type
 * @property string|null $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|Option query()
 * @mixin Eloquent
 */
class Indices extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_LOCKED = 'locked';

    public static function cleanUp(array $names) {
        self::whereNotIn('name', $names)->delete();
    }

    public static function deleteByName(string $name) {
        self::where('name', $name)->delete();
    }

    public static function getStatus(string $name): string
    {
        return self::where('name', $name)->pluck('status')->first() ?? self::STATUS_ACTIVE;
    }
}
