<?php

namespace Tests\Unit\Traits\HasTranslations;

use App\Models\Eloquent\Category;
use App\Traits\Eloquent\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tests\Unit\Traits\HasTranslations\DummyBroken
 *
 * @property int $id
 * @property string $test_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|DummyTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @mixin Eloquent
 */
class DummyBroken extends Model
{
    use HasTranslations;

    protected string $translationModelNamespace = 'Im\A\Broken\Namespace';

    protected $fillable = [
        'test_column',
    ];
}
