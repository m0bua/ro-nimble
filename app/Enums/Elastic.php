<?php
/**
 * Class Elastic
 * @package App\Enums
 */

namespace App\Enums;

class Elastic
{
    /**
     * Параметры/поля для построения запросов в elasticsearch
     */
    public const PARAM_SIZE = 'size';
    public const PARAM_TRACK_TOTAL_HITS = 'track_total_hits';

    public const FIELD_ID = 'id';
    public const FIELD_PRICE = 'price';
    public const FIELD_PRODUCER = 'producer_id';
    public const FIELD_MERCHANT = 'merchant_type';
    public const FIELD_STATUS_INHERITED = 'status_inherited';
    public const FIELD_STATE = 'state';
    public const FIELD_GROUP_ID = 'group_id';
    public const FIELD_IS_GROUP_PRIMARY = 'is_group_primary';
    public const FIELD_SERIES = 'series_id';
    public const FIELD_PAYMENT_IDS = 'payment_ids';
    public const FIELD_PAYMENT_METHOD_IDS = 'payment_method_ids';
    public const FIELD_SELL_STATUS = 'sell_status';
    public const FIELD_CATEGORIES_PATH = 'categories_path';
    public const FIELD_CATEGORY_ID = 'category_id';
    public const FIELD_PROMOTION = 'promotion';
    public const FIELD_PROMOTION_ID = 'promotion.id';
    public const FIELD_PROMOTION_IDS = 'promotion_ids';
    public const FIELD_OPTIONS = 'options';
    public const FIELD_OPTION_VALUES = 'option_values';
    public const FIELD_OPTION_SLIDERS = 'option_sliders';
    public const FIELD_OPTION_SLIDERS_ID = 'option_sliders.id';
    public const FIELD_OPTION_SLIDERS_VALUE = 'option_sliders.value';
    public const FIELD_OPTION_CHECKED = 'option_checked';
    public const FIELD_COUNTRY_CODE = 'country_code';
    public const FIELD_GROUP_TOKEN = 'group_token';
    public const FIELD_BONUS = 'bonus_charge_pcs';
    public const FIELD_PRODUCER_TITLE_KEYWORD = 'producer_title.keyword';
    public const FIELD_PRODUCER_TITLE_TEXT = 'producer_title.text';
    public const FIELD_GOODS_LABELS_IDS = 'goods_labels_ids';
}
