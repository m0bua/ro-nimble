<?php

namespace App\Enums;

class Config
{
    /**
     * К-во товаров при выборке
     */
    public const ELASTIC_DEFAULT_SIZE = 10000;

    /**
     * К-во товаров н астранице
     */
    public const CATALOG_GOODS_LIMIT = 60;

    /**
     * Дефолтное количество товаров в short листе
     */
    public const SHORT_LIST_ELEMENTS_COUNT = 12;

    /**
     * Параметры при аггрегации
     */
    public const ELASTIC_DEFAULT_FILTER_SIZE = 0;
    public const FILTERS_AGGREGATIONS_LIMIT = 2000;

    /**
     * Опция "Оплата частями" для фильтра "Товары с акциями"
     */
    public const INSTALLMENT_OPTION = 58432;
}
