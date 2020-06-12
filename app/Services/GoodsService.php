<?php

namespace App\Services;

use goods\graphqlmodels\models\Goods;


class GoodsService
{
    public function getSelectFields()
    {
        return [
            'id',
            'name',
            'title',
            'mpath',
            'price',
            'href',
            'name',
            'docket',
            'is_group_primary',
            'status',
            'sell_status',
            'promo_title_part',
            'old_price',
            'price_pcs',
            'comments_amount',
            'comments_mark',
            'seller_id',
            'merchant_id',
            'state',
        ];
    }

    public function getById($id)
    {
        $id = 200775625;
        $id = 97653;

        $model = new Goods();
        $model->selectFields($this->getSelectFields());
        $model->selectCategory(['id', 'title']);
        $model->selectProducer(['id', 'title']);
        $model->selectTags(['title']);
//        $model->selectAttachments(['id', 'url']);
        $model->selectOptions(['option_id', 'details' => ['title'], 'value', 'values' => ['title'], 'type']);

        $result = $model->getById($id);

        if ($model->hasRemoteErrors()) {
            return $model->getRemoteErrors();
        }

        return $result;
    }

    public function getByIds($ids)
    {
        $this->model->selectFields(['id', 'name', 'title']);
        $result = $this->model->getByIds([200775625, 200828737]);
    }


}
