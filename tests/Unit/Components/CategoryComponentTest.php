<?php

namespace Tests\Unit\Components;

use App\Components\ElasticSearchComponents\CategoryComponent;
use App\Filters\Components\Category;
use App\Filters\Filters;
use App\Helpers\ElasticWrapper;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class CategoryComponentTest extends TestCase
{
    private Prophet $prophet;
    private ElasticWrapper $esWrapper;

    protected function setUp(): void
    {
        $this->prophet = new Prophet;
        $this->esWrapper = new ElasticWrapper();
    }

    private function getComponentByCategoryId(int $categoryId): CategoryComponent
    {
        $filters = $this->prophet->prophesize(Filters::class)->reveal();
        $category = $this->prophet->prophesize(Category::class);
        $collection = $this->prophet->prophesize(Collection::class);
        $category->getValues()->willReturn($collection);
        $collection->first()->willReturn($categoryId);
        $collection->reveal();
        $filters->category = $category->reveal();

        return new CategoryComponent($filters, $this->esWrapper);
    }

    public function categoriesProvider()
    {
        $categoriesIds = [];
        foreach (CategoryComponent::EXCLUDED_SUB_CATEGORIES as $categoryId => $subCategoriesIds) {
            $categoriesIds[] = [$categoryId];
        }

        return $categoriesIds;
    }

    /**
     * @test
     * @dataProvider categoriesProvider
     */
    public function testGetValue(int $categoryId)
    {
        $this->assertEquals(
            $this->getComponentByCategoryId($categoryId)->getValue(),
            ['term' => ['categories_path' => $categoryId]]
        );
    }

    public function excludedCategoriesProvider()
    {
        $subCategoriesIds = [];
        foreach (CategoryComponent::EXCLUDED_SUB_CATEGORIES as $categoryId => $subCategories) {
            $subCategoriesIds[] = [$categoryId, $subCategories];
        }

        return $subCategoriesIds;
    }

    /**
     * @test
     * @dataProvider excludedCategoriesProvider
     */
    public function testGetExcludedValue(int $categoryId, array $excludedSubCategories)
    {
        $this->assertEquals(
            $this->getComponentByCategoryId($categoryId)->getExcludedValue(),
            ['terms' => ['categories_path' => $excludedSubCategories]]
        );
    }

    public function isExcludedCategoriesProvider()
    {
        $categoriesIds = [];
        foreach (CategoryComponent::EXCLUDED_SUB_CATEGORIES as $categoryId => $subCategoriesIds) {
            $categoriesIds[] = [$categoryId, true];
        }
        $categoriesIds[] = [111, false];

        return $categoriesIds;
    }

    /**
     * @test
     * @dataProvider isExcludedCategoriesProvider
     */
    public function testIsExcludedCategoryExists(int $categoryId, bool $result)
    {
        $this->assertEquals($result, $this->getComponentByCategoryId($categoryId)->isExcludedCategoryExists());
    }
}
