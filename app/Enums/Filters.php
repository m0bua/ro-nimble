<?php

namespace App\Enums;

class Filters
{
    /**
     * Фильтра
     */
    // Страница
    public const PAGE = 'page';
    // Количество на странице
    public const PER_PAGE = 'perPage';
    // ID Категории
    public const CATEGORY = 'category';
    // "Категория"
    public const CATEGORIES = 'categories';
    // ID Акции
    public const PROMOTION = 'promotion';
    // Сортировка
    public const SORT = 'sort';
    // ID секции фильтра "Все товары"
    public const SECTION = 'section';
    // "Производитель"
    public const PRODUCERS = 'producers';
    // "Цена"
    public const PRICE = 'price';
    // "Продавец"
    public const SELLER = 'seller';
    // "Программа лояльности" (опция "С бонусами")
    public const BONUS = 'bonus';
    // "Новый - б/у"
    public const STATE = 'state';
    // "Серия"
    public const SERIES = 'series';
    // Сгруппированные товары
    public const SINGLE_GOODS = 'singleGoods';
    // "Товары с акциями"
    public const PROMOTION_GOODS = 'goodsWithPromotions';
    // Код страны
    public const COUNTRY = 'country';
    // "Статус товара"
    public const SELL_STATUS = 'sellStatus';
    // "Язык"
    public const LANG = 'lang';

    // Динамические опции фильтров
    public const OPTIONS = 'options';
    public const OPTION_VALUES = 'optionValues';
    public const OPTION_CHECKED = 'optionChecked';
    public const OPTION_SLIDERS = 'optionSliders';

    /**
     * GET параметры фильтров
     */
    public const PARAM_PAGE = 'page';
    public const PARAM_PER_PAGE = 'per_page';
    public const PARAM_CATEGORY = 'category_id';
    public const PARAM_CATEGORIES = 'categories';
    public const PARAM_PROMOTION = 'promotion_id';
    public const PARAM_SORT = 'sort';
    public const PARAM_SECTION = 'section_id';
    public const PARAM_PRODUCERS = 'producer';
    public const PARAM_PRICE = 'price';
    public const PARAM_SELLER = 'seller';
    public const PARAM_BONUS = 'with_bonus';
    public const PARAM_STATE = 'state';
    public const PARAM_SERIES = 'series';
    public const PARAM_SINGLE_GOODS = 'single_goods';
    public const PARAM_PROMOTION_GOODS = 'goods_with_promotions';
    public const PARAM_COUNTRY = 'country';
    public const PARAM_SELL_STATUS = 'sell_status';
    public const PARAM_LANG = 'lang';

    /**
     * Дополнительные параметры динамических фильтров
     */
    // черная пятница
    public const PARAM_RASPRODAGA = 'rasprodaga';
    // готов к отправке
    public const PARAM_GOTOVO_K_OTPRAVKE = 'gotovo-k-otpravke';

    /**
     * Дефолтное значение фильтра
     */
    public const DEFAULT_FILTER_VALUE = [];

    /**
     * Доступные фильтра
     * @var array|string[]
     */
    public static array $attributes = [
        self::PAGE,
        self::PER_PAGE,
        self::CATEGORY,
        self::CATEGORIES,
        self::PROMOTION,
        self::SORT,
        self::SECTION,
        self::PRODUCERS,
        self::PRICE,
        self::SELLER,
        self::BONUS,
        self::STATE,
        self::SERIES,
        self::SINGLE_GOODS,
        self::PROMOTION_GOODS,
        self::COUNTRY,
        self::SELL_STATUS,
        self::PARAM_LANG,
        self::OPTIONS,
    ];

    /**
     * Параметры статических фильтров, для определения динамических
     * @var array|string[]
     */
    public static array $staticFiltersParams = [
        self::PARAM_PAGE,
        self::PARAM_PER_PAGE,
        self::PARAM_CATEGORY,
        self::PARAM_CATEGORIES,
        self::PARAM_PROMOTION,
        self::PARAM_SORT,
        self::PARAM_SECTION,
        self::PARAM_PRODUCERS,
        self::PARAM_PRICE,
        self::PARAM_SELLER,
        self::PARAM_BONUS,
        self::PARAM_STATE,
        self::PARAM_SERIES,
        self::PARAM_SELL_STATUS,
        self::PARAM_SINGLE_GOODS,
        self::PARAM_PROMOTION_GOODS,
        self::PARAM_COUNTRY,
        self::PARAM_LANG,
    ];

    /**
     * Список фильтров для общего скрытия/отображения
     * @var array|string[]
     */
    public static array $toggleFilters = [
        self::CATEGORIES,
        self::PROMOTION,
        self::SECTION,
        self::PRODUCERS,
        self::PRICE,
        self::SELLER,
        self::BONUS,
        self::STATE,
        self::SERIES,
        self::SINGLE_GOODS,
        self::PROMOTION_GOODS,
        self::SELL_STATUS,
        self::OPTIONS,
    ];

    /**
     * Доступные динамичесчие фильтра
     * @var string[]
     */
    public static $dynamicFiltersAttributes = [
        self::OPTION_VALUES,
        self::OPTION_CHECKED,
        self::OPTION_SLIDERS,
    ];

    /**
     * Параметры фильтра "Сортировка"
     */
    // От дешевым к дорогим
    public const SORT_CHEAP = 'cheap';
    // От дорогих к дешевым
    public const SORT_EXPENSIVE = 'expensive';
    // По популяности
    public const SORT_POPULARITY = 'popularity';
    // Новинки
    public const SORT_NOVELTY = 'novelty';
    // Акционные
    public const SORT_ACTION = 'action';
    // По рейтингу
    public const SORT_RANK = 'rank';

    /**
     * Параметры фильтра "Продавец"
     */
    // Продавец Rozetka
    public const SELLER_ROZETKA = 'rozetka';
    // Другие продавцы
    public const SELLER_OTHER = 'other';
    // Продавцы с определенным признаком
    public const SELLER_FULFILLMENT = 'fulfillment';

    /**
     * Параметры фильтра "Б/у - Новый" (State)
     */
    public const STATE_NEW = 'new';
    public const STATE_USED = 'used';
    public const STATE_REFURBISHED = 'refurbished';

    /**
     * Параметры фильтра "Товары с акциями" (GoodsWithPromotions)
     */
    // Оплата частями
    public const PROMOTION_GOODS_INSTALLMENT = 'installments';
    // Акция
    public const PROMOTION_GOODS_PROMOTION = 'promotion';

    /**
     * Теги
     */
    // Акция
    public const TAGS_ACTION = 4;
    // Новинка-акция
    public const TAGS_PROMOTION_NOVELTY = 7;

    /**
     * Набор тегов для опции "Акция" фильтра "Товары с акциями"
     * @var int[]
     */
    public static $filterPromotionTags = [
        self::TAGS_ACTION,
        self::TAGS_PROMOTION_NOVELTY,
    ];

    /**
     * Параметры фильтра "Страна"
     */
    public const COUNTRY_UA = 'ua';
    public const COUNTRY_UZ = 'uz';

    /**
     * Параметры фильтра "Статус товара"
     */
    // Ожидается
    public const SELL_STATUS_WAITING_FOR_SUPPLY = 'waiting_for_supply';
    //Заканчивается
    public const SELL_STATUS_LIMITED = 'limited';
    // Есть в наличии
    public const SELL_STATUS_AVAILABLE = 'available';
    // Закончился
    public const SELL_STATUS_OUT_OF_STOCK = 'out_of_stock';
    // Нет в наличии
    public const SELL_STATUS_UNAVAILABLE = 'unavailable';
    // Архивный
    public const SELL_STATUS_ARCHIVE = 'archive';
    // Скрытый
    public const SELL_STATUS_HIDDEN = 'hidden';

    /**
     * Список статусов товаров для выдачи
     * @var array
     */
    public static $sellActiveStatuses = [
        self::SELL_STATUS_UNAVAILABLE,
        self::SELL_STATUS_WAITING_FOR_SUPPLY,
        self::SELL_STATUS_LIMITED,
        self::SELL_STATUS_AVAILABLE,
        self::SELL_STATUS_OUT_OF_STOCK,
    ];

    /**
     * Список статусов товаров для выдачи категорий фешен
     * @var array
     */
    public static $sellActiveStatusesFashion = [
        self::SELL_STATUS_LIMITED,
        self::SELL_STATUS_AVAILABLE,
    ];

    /**
     * Параметры поля "status_inherited"
     */
    public const STATUS_INHERITED_ACTIVE = 'active';

    /**
     * Значения параметра special_combobox_view
     */
    public const SPECIAL_COMBOBOX_VIEW_SLIDER = 'slider';
    public const SPECIAL_COMBOBOX_VIEW_LIST = 'list';
    public const SPECIAL_COMBOBOX_VIEW_SECTION_LIST_AUTOCOMPLETE = 'section_list_autocomplete';
    public const SPECIAL_COMBOBOX_VIEW_TREE = 'tree';

    /**
     * Значения параметра option_type
     */
    public const OPTION_TYPE_SLIDER = 'Slider';
    public const OPTION_TYPE_LIST = 'List';
    public const OPTION_TYPE_COMBOBOX = 'ComboBox';

    /**
     * Значения параметра comparable
     */

    /**
     * Признак для фильтра из сайд-бара
     */
    public const COMPARABLE_MAIN = 'main';
    /**
     * Признак для фильтра-тега
     */
    public const COMPARABLE_BOTTOM = 'bottom';
    /**
     * Отключен
     */
    public const COMPARABLE_DISABLE = 'disable';
    /**
     * Заблокирован
     */
    public const COMPARABLE_LOCKED = 'locked';
}
