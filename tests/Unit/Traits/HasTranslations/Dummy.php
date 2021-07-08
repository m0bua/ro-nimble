<?php

namespace Tests\Unit\Traits\HasTranslations;

use App\Casts\Translatable;
use App\Models\Eloquent\Category;
use App\Traits\Eloquent\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tests\Unit\Traits\HasTranslations\Dummy
 *
 * @property int $id
 * @property string $test_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property array<string> $title
 * @property-read Collection|DummyTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @mixin Eloquent
 */
class Dummy extends Model
{
    use HasTranslations;

    protected $fillable = [
        'test_column',
    ];

    protected $casts = [
        'title' => Translatable::class,
    ];
}
