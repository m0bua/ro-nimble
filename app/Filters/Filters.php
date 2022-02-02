<?php

namespace App\Filters;

use App\Filters\Contracts\FiltersInterface;
use App\Http\Requests\FilterRequest;
use App\Filters\Components\Page;
use App\Filters\Components\PerPage;
use App\Filters\Components\Category;
use App\Filters\Components\Categories;
use App\Filters\Components\Promotion;
use App\Filters\Components\Sort;
use App\Filters\Components\Section;
use App\Filters\Components\Producers;
use App\Filters\Components\Price;
use App\Filters\Components\Sellers;
use App\Filters\Components\Bonus;
use App\Filters\Components\States;
use App\Filters\Components\Series;
use App\Filters\Components\SingleGoods;
use App\Filters\Components\GoodsWithPromotions;
use App\Filters\Components\Country;
use App\Filters\Components\SellStatuses;
use App\Filters\Components\Lang;
use App\Filters\Components\Options;
use App\Filters\Components\Query;
use Exception;

class Filters implements FiltersInterface
{
    /**
     * @var Page
     */
    public Page $page;

    /**
     * @var PerPage
     */
    public PerPage $perPage;

    /**
     * @var Category
     */
    public Category $category;

    /**
     * @var Categories
     */
    public Categories $categories;

    /**
     * @var Promotion
     */
    public Promotion $promotion;

    /**
     * @var Sort
     */
    public Sort $sort;

    /**
     * @var Section
     */
    public Section $section;

    /**
     * @var Producers
     */
    public Producers $producers;

    /**
     * @var Price
     */
    public Price $price;

    /**
     * @var Sellers
     */
    public Sellers $sellers;

    /**
     * @var Bonus
     */
    public Bonus $bonus;

    /**
     * @var States
     */
    public States $states;

    /**
     * @var Series
     */
    public Series $series;

    /**
     * @var SingleGoods
     */
    public SingleGoods $singleGoods;

    /**
     * @var GoodsWithPromotions
     */
    public GoodsWithPromotions $goodsWithPromotions;

    /**
     * @var Country
     */
    public Country $country;

    /**
     * @var SellStatuses
     */
    public SellStatuses $sellStatuses;

    /**
     * @var Lang
     */
    public Lang $lang;

    /**
     * @var Options
     */
    public Options $options;

    /**
     * @var Query
     */
    public Query $query;

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
