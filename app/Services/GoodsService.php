<?php

namespace App\Services;

use goods\graphqlmodels\models\Goods;

class GoodsService
{
    /**
     * @var Goods
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Goods();
    }

    public function reloadModel()
    {
        $this->model = new Goods();
    }

    /**
     * Список полей для good
     *
     * @return string[]
     */
    public function getSelectFields()
    {
        return [
            'id',
            'title',
            'price',
            'old_price',
            'price_pcs',
            'href',
            'comments_amount',
            'sell_status',
            'category_id',
            'seller_id',
            'merchant_id',
            'group_id',
            'state',
            'docket',
            'mpath',
            'is_group_primary',
            'status',
            'promo_title_part',
            'comments_mark',
            'order',
            'is_deleted'
        ];
    }

    /**
     * Получение данных по одному good по id
     *
     * @param $id
     * @return \goods\graphqlmodels\RemoteError|object|null
     */
    public function getById($id)
    {
        $this->model
            ->selectFields($this->getSelectFields())
            ->selectUk(['title', 'docket', 'promo_title_part'])
            ->selectMpathCategories(['id', 'title', 'name'])
            ->selectCategory(['id', 'title'])
            ->selectProducer(['id', 'title'])
            ->selectTags(['id', 'title', 'name', 'priority'])
            ->selectAttachments(['url', 'order', 'variant', 'group_name']);

        $data = $this->model->getById($id);

        if ($this->model->hasRemoteErrors()) {
            return $this->model->getRemoteErrors();
        }

        $result = $this->getResult($data);

        $this->reloadModel();

        $this->model->selectOptions(['option_id', 'values' => ['id']]);

        $data = $this->model->getById($id);

        if ($this->model->hasRemoteErrors()) {
            return $this->model->getRemoteErrors();
        }

        $result = array_merge($result, $this->getResult($data));

        return $result;
    }

    /**
     * transform object to array
     *
     * @param $result
     * @return mixed
     */
    public function getResult($result)
    {
        return json_decode(json_encode($result), true);
    }
}
