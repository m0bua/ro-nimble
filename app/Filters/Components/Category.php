<?php
/**
 * Класс для работы с фильтром "ID Категории"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use \App\Models\Eloquent\Category as CategoryModel;

class Category extends AbstractFilter
{
    public const FASHION_CATEGORY_ID = 1162030;

    /**
     * @var CategoryModel|null
     */
    private ?CategoryModel $currentCategory;

    /**
     * @var string
     */
    protected string $name = Filters::CATEGORY;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * @var bool
     */
    protected bool $ignoreAutoranking = false;

    /**
     * Category constructor.
     * @param array $values
     */
    public function __construct(array $values, ?CategoryModel $currentCategory = null)
    {
        $this->values = $values;
        $this->currentCategory = $currentCategory;
    }

    /**
     * @param FilterRequest $request
     * @return Category
     */
    public static function fromRequest(FormRequest $request): Category
    {
        $categoryId = abs((int) $request->input(Filters::PARAM_CATEGORY));

        if (!$categoryId) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        /** @var CategoryModel $category */
        $category = CategoryModel::getById($categoryId);

        return $category
            ? new static([$category->id], $category)
            : new static(Filters::DEFAULT_FILTER_VALUE);
    }

    /**
     * @return CategoryModel|null
     */
    public function getCategory(): ?CategoryModel
    {
        return $this->currentCategory;
    }

    /**
     * @return bool
     */
    public function isRozetkaTop(): bool
    {
        return $this->getCategory() && $this->getCategory()->is_rozetka_top;
    }

    /**
     * @return array
     */
    public function getParentsCategories(): array
    {
        if (!$this->getCategory()) {
            return [];
        }

        return array_values(array_filter(
            explode('.', sprintf('%s.%s.', $this->currentCategory->mpath, $this->currentCategory->id))
        ));
    }

    /**
     * @return bool
     */
    public function isFashion(): bool
    {
        return in_array(self::FASHION_CATEGORY_ID, $this->getParentsCategories());
    }

    /**
     * @return int[]
     */
    public function getAutorankingCategoriesList(): array
    {
        return [
            self::FASHION_CATEGORY_ID
        ];
    }

    /**
     * @return bool
     */
    public function isAutorankingCategory(): bool
    {
        if ($this->ignoreAutoranking) {
            return false;
        }

        return !!array_intersect($this->getAutorankingCategoriesList(), $this->getParentsCategories());
    }

    /**
     * @return void
     */
    public function disableAutoranking(): void
    {
        $this->ignoreAutoranking = true;
    }
}
