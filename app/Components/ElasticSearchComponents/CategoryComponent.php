<?php
/**
 * Класс для генерации параметра "categories_path" для фильтра "category_id"
 * Class CategoryComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;
use App\Filters\Filters;
use App\Helpers\ElasticWrapper;

class CategoryComponent extends BaseComponent
{
    const EXCLUDED_SUB_CATEGORIES = [
        1162070 => [4637703],
        2033137 => [4628751],
    ];

    /** Sometimes categoryId will be null */
    private ?int $categoryId;

    public function __construct(Filters $filters, ElasticWrapper $elasticWrapper)
    {
        parent::__construct($filters, $elasticWrapper);
        $this->categoryId = $this->filters->category->getValues()->first();
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_CATEGORIES_PATH, $this->categoryId);
    }

    public function getExcludedValue(): array
    {
        return $this->elasticWrapper->terms(Elastic::FIELD_CATEGORIES_PATH, self::EXCLUDED_SUB_CATEGORIES[$this->categoryId]);
    }

    public function isExcludedCategoryExists(): bool
    {
        return array_key_exists($this->categoryId, self::EXCLUDED_SUB_CATEGORIES);
    }
}
