<?php

namespace Tests\Unit\Macros;

use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SelectTranslationTest extends TestCase
{
    public function testItCanSelectAllTargetTranslations(): void
    {
        $ids = $this->setUpDatabase(2, ['title', 'description']);
        App::setLocale(Language::UK);

        OptionValue::query()
            ->select(['id'])
            ->selectTranslations(['title', 'description'])
            ->whereIn('id', $ids)
            ->toBase()
            ->get()
            ->each(function (object $optionValue) {
                $this->assertEquals("Option Value $optionValue->id title uk", $optionValue->title);
                $this->assertEquals("Option Value $optionValue->id description uk", $optionValue->description);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItCanSelectAllFallbackTranslations(): void
    {
        $ids = $this->setUpDatabase(2, ['title', 'description'], [Language::RU]);
        App::setLocale(Language::UK);

        OptionValue::query()
            ->select(['id'])
            ->selectTranslations(['title', 'description'])
            ->whereIn('id', $ids)
            ->toBase()
            ->get()
            ->each(function (object $optionValue) {
                $this->assertEquals("Option Value $optionValue->id title ru", $optionValue->title);
                $this->assertEquals("Option Value $optionValue->id description ru", $optionValue->description);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItReturnsNullIfTranslationNotFound(): void
    {
        $ids = $this->setUpDatabase(1, []);

        OptionValue::query()
            ->select(['id'])
            ->selectTranslation('title')
            ->whereIn('id', $ids)
            ->toBase()
            ->get()
            ->each(function (object $optionValue) {
                $this->assertNull($optionValue->title);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItReturnsDifferentTranslations(): void
    {
        $entityWithTargetTranslation = OptionValue::factory()->create();
        $entityWithTargetTranslation->title = [
            Language::UK => "title $entityWithTargetTranslation->id uk"
        ];

        $entityWithFallbackTranslation = OptionValue::factory()->create();
        $entityWithFallbackTranslation->title = [
            Language::RU => "title $entityWithFallbackTranslation->id ru"
        ];

        $entityWIthNoTranslation = OptionValue::factory()->create();

        $ids = [$entityWithTargetTranslation->id, $entityWithFallbackTranslation->id, $entityWIthNoTranslation->id];

        App::setLocale(Language::UK);
        $result = OptionValue::query()
            ->select(['id'])
            ->selectTranslation('title')
            ->whereIn('id', $ids)
            ->toBase()
            ->get()
            ->mapWithKeys(fn(object $optionValue) => [$optionValue->id => $optionValue]);

        $this->assertEquals("title $entityWithTargetTranslation->id uk", $result[$entityWithTargetTranslation->id]->title);
        $this->assertEquals("title $entityWithFallbackTranslation->id ru", $result[$entityWithFallbackTranslation->id]->title);
        $this->assertNull($result[$entityWIthNoTranslation->id]->title);

        $this->clearDatabase($ids);
    }

    /**
     * @param int $count count of created entities
     * @param array $fields translatable fields
     * @param array $languages list of languages
     */
    private function setUpDatabase(
        int   $count,
        array $fields,
        array $languages = [Language::UK, Language::RU]
    ): Collection
    {
        return OptionValue::factory()
            ->count($count)
            ->create()
            ->each(function (OptionValue $optionValue) use ($fields, $languages) {
                foreach ($fields as $field) {
                    foreach ($languages as $language) {
                        $optionValue->$field = [
                            $language => "Option Value $optionValue->id $field $language",
                        ];
                    }
                }
            })->pluck('id');
    }

    /**
     * @param array $ids
     */
    private function clearDatabase(array $ids): void
    {
        OptionValue::query()->whereIn('id', $ids)->delete();
    }
}
