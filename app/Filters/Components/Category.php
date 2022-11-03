<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use App\Models\Eloquent\CategoryOption;
use App\Models\Eloquent\Option;
use Illuminate\Foundation\Http\FormRequest;
use \App\Models\Eloquent\Category as CategoryModel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "ID Категории"
 *
 * @OA\Parameter (
 *     name="category_id",
 *     in="query",
 *     required=false,
 *     description="Текущая категория",
 *     example="category_id[]=1234567",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="integer"
 *         )
 *     )
 * )
 */
class Category extends AbstractFilter
{
    public const FASHION_CATEGORY_ID = 1162030;

    protected const PARAM = Filters::PARAM_CATEGORY;

    /**
     * Id опции для авторанжирования
     */
    public const AUTO_RANKING_OPTION_ID = 100475;

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
        $requestCategory = $request->input(self::PARAM);
        if (!\is_array($requestCategory) || empty($requestCategory[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        $categoryId = $requestCategory[0];
        $error = sprintf('\'%s\' parameter must be positive integer', self::PARAM);

        if (!is_numeric($categoryId)) {
            throw new BadRequestHttpException($error);
        }

        $categoryId = (int)$categoryId;

        if ($categoryId < 1) {
            throw new BadRequestHttpException($error);
        }

        /** @var CategoryModel $category */
        $category = CategoryModel::getById($categoryId);

        if (empty($category)) {
            if (empty($request->input(Filters::PARAM_PROMOTION))) {
                throw new BadRequestHttpException('Caterory not found');
            } else {
                return new static(Filters::DEFAULT_FILTER_VALUE);
            }
        }

        return new static([$category->id], $category);
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
     * @return bool
     */
    public function isFilterAutoranking(): bool
    {
        return $this->getCategory()
            && CategoryOption::getCategoryOption($this->currentCategory->id, self::AUTO_RANKING_OPTION_ID)
            && Option::getOptionAutorankingCount($this->currentCategory) >= 3;
    }

    /**
     * @return int[]
     */
    public function getAutorankingCategories(): array
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

        return !!array_intersect($this->getAutorankingCategories(), $this->getParentsCategories());
    }

    /**
     * @return void
     */
    public function disableAutoranking(): void
    {
        $this->ignoreAutoranking = true;
    }
}
