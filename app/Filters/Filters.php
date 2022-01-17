<?php

namespace App\Filters;

use App\Filters\Contracts\FiltersInterface;
use App\Http\Requests\FilterRequest;
use App\Filters\Components;
use Exception;

class Filters implements FiltersInterface
{
    /**
     * @var Components\Page
     */
    public Components\Page $page;

    /**
     * @var Components\PerPage
     */
    public Components\PerPage $perPage;

    /**
     * @var Components\Category
     */
    public Components\Category $category;

    /**
     * @var Components\Categories
     */
    public Components\Categories $categories;

    /**
     * @var Components\Promotion
     */
    public Components\Promotion $promotion;

    /**
     * @var Components\Sort
     */
    public Components\Sort $sort;

    /**
     * @var Components\Section
     */
    public Components\Section $section;

    /**
     * @var Components\Producers
     */
    public Components\Producers $producers;

    /**
     * @var Components\Price
     */
    public Components\Price $price;

    /**
     * @var Components\Seller
     */
    public Components\Seller $seller;

    /**
     * @var Components\Bonus
     */
    public Components\Bonus $bonus;

    /**
     * @var Components\State
     */
    public Components\State $state;

    /**
     * @var Components\Series
     */
    public Components\Series $series;

    /**
     * @var Components\SingleGoods
     */
    public Components\SingleGoods $singleGoods;

    /**
     * @var Components\GoodsWithPromotions
     */
    public Components\GoodsWithPromotions $goodsWithPromotions;

    /**
     * @var Components\Country
     */
    public Components\Country $country;

    /**
     * @var Components\SellStatus
     */
    public Components\SellStatus $sellStatus;

    /**
     * @var Components\Lang
     */
    public Components\Lang $lang;

    /**
     * @var Components\Options
     */
    public Components\Options $options;

    /**
     * Filter constructor.
     * @param array $attributes
     * @throws Exception
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $attribute) {
            if (!property_exists($this, $name)) {
                throw new Exception("Unknown property $name");
            }

            $this->$name = $attribute;
        }
    }

    /**
     * Create new instance from Request
     *
     * @param FilterRequest $request
     * @return Filters
     * @throws Exception
     */
    public static function fromRequest(FilterRequest $request): Filters
    {
        $attributes = array_flip(\App\Enums\Filters::$attributes);

        foreach ($attributes as $key => &$attribute) {
            $className = __NAMESPACE__ . '\\Components\\' . ucfirst($key);
            if (!class_exists($className)) {
                throw new Exception("Missed class for $key attribute");
            } elseif (!method_exists($className, 'fromRequest')) {
                throw new Exception("Unable to create $className from Request");
            }

            $attribute = $className::fromRequest($request);
        }

        return new static($attributes);
    }

    /**
     * Скрыть все фильтра
     * @return void
     * @throws Exception
     */
    public function hideFilters()
    {
        foreach (\App\Enums\Filters::$toggleFilters as $filter) {
            $this->$filter->hideValues();
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function showFilters()
    {
        foreach (\App\Enums\Filters::$toggleFilters as $filter) {
            $this->$filter->showValues();
        }
    }

    /**
     * @return bool
     */
    public function isHasFilters(): bool
    {
        foreach (\App\Enums\Filters::$toggleFilters as $filter) {
            if ($this->$filter->getValues()->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }
}
