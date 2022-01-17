<?php

namespace Tests\Unit\Macros;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryOption;
use App\Support\Language;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SelectNestedTranslationTest extends TestCase
{
    public function testItCanSelectAllTargetTranslations(): void
    {
        $ids = $this->setUpDatabase(2);
        App::setLocale(Language::UK);

        Category::query()->from('categories', 'c')
            ->select(['c.id', 'co.id as category_option_id'])
            ->leftJoin('category_options as co', 'c.id', 'co.category_id')
            ->selectNestedTranslation(CategoryOption::class, 'value', '', 'co')
            ->toBase()
            ->get()
            ->each(function (object $category) {
                $this->assertEquals("category option $category->category_option_id value uk", $category->value);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItCanSelectAllFallbackTranslations(): void
    {
        $ids = $this->setUpDatabase(2, [Language::RU]);
        App::setLocale(Language::UK);

        Category::query()->from('categories', 'c')
            ->select(['c.id', 'co.id as category_option_id'])
            ->leftJoin('category_options as co', 'c.id', 'co.category_id')
            ->selectNestedTranslation(CategoryOption::class, 'value', '', 'co')
            ->toBase()
            ->get()
            ->each(function (object $category) {
                $this->assertEquals("category option $category->category_option_id value ru", $category->value);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItReturnsNullIfTranslationNotFound(): void
    {
        $ids = $this->setUpDatabase(1, []);

        Category::query()->from('categories', 'c')
            ->select(['c.id', 'co.id as category_option_id'])
            ->leftJoin('category_options as co', 'c.id', 'co.category_id')
            ->selectNestedTranslation(CategoryOption::class, 'value', '', 'co')
            ->toBase()
            ->get()
            ->each(function (object $category) {
                $this->assertNull($category->value);
            });

        $this->clearDatabase($ids->toArray());
    }

    public function testItReturnsDifferentTranslations(): void
    {
        $entity1 = Category::factory()->create();
        $withTargetTranslation = CategoryOption::factory()->create(['category_id' => $entity1->id]);
        $withTargetTranslation->value = [
            Language::UK => "value $entity1->id uk"
        ];

        $entity2 = Category::factory()->create();
        $withFallbackTranslation = CategoryOption::factory()->create(['category_id' => $entity2->id]);
        $withFallbackTranslation->value = [
            Language::RU => "value $entity2->id ru"
        ];

        $entity3 = Category::factory()->create();
        CategoryOption::factory()->create(['category_id' => $entity3->id]);

        $ids = [$entity1->id, $entity2->id, $entity3->id];

        App::setLocale(Language::UK);
        $result = Category::query()->from('categories', 'c')
            ->select(['c.id', 'co.id as category_option_id'])
            ->leftJoin('category_options as co', 'c.id', 'co.category_id')
            ->selectNestedTranslation(CategoryOption::class, 'value', '', 'co')
            ->toBase()
            ->get()
            ->mapWithKeys(fn(object $category) => [$category->id => $category]);

        $this->assertEquals("value $entity1->id uk", $result[$entity1->id]->value);
        $this->assertEquals("value $entity2->id ru", $result[$entity2->id]->value);
        $this->assertNull($result[$entity3->id]->value);

        $this->clearDatabase($ids);
    }

    /**
     * @param int $count count of created entities
     * @param array $languages list of languages
     * @return Collection
     */
    private function setUpDatabase(
        int   $count,
        array $languages = [Language::UK, Language::RU]
    ): Collection
    {
        return Category::factory()
            ->count($count)
            ->create()
            ->each(function (Category $category) use ($languages) {
                $categoryOption = CategoryOption::factory()->create(['category_id' => $category->id]);

                foreach ($languages as $language) {
                    $categoryOption->value = [
                        $language => "category option $categoryOption->id value $language",
                    ];
                }
            })->pluck('id');
    }

    /**
     * @param array $ids
     */
    private function clearDatabase(array $ids): void
    {
        Category::query()->whereIn('id', $ids)->delete();
        CategoryOption::query()->whereIn('category_id', $ids)->delete();
    }
}
