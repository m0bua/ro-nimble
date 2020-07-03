<?php

namespace App\Library\Services;

use App\Models\GraphQL\GoodsModel;
use GraphQL\InlineFragment;
use GraphQL\Query;
use GraphQL\RawObject;

/**
 * @deprecated
 *
 * Class GoodsService
 * @package App\Library\Services
 */
class GoodsService
{
    /**
     * Список полей для goods
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
     * Получение данных по одному goods по id
     *
     * @param $id
     * @return array|object
     */
    public function getById($id)
    {

//        $basicQuery = (new Query('goodsOne'))
//            ->setArguments(['where' => new RawObject('{id_eq: 198516121}')])
//            ->setSelectionSet(
//                array_merge(
//                    $this->getSelectFields(),
//                    [
//                        (new Query('options'))
//                            ->setSelectionSet([
//                                (new Query('details'))
//                                    ->setSelectionSet([
//                                        'id',
//                                        'title',
//                                        'name',
//                                        'type',
//                                    ]),
//                                (new InlineFragment('GoodsOptionSingle'))->setSelectionSet(['value']),
//                                (new InlineFragment('GoodsOptionPlural'))
//                                    ->setSelectionSet(
//                                        [
//                                            (new Query('values'))
//                                            ->setSelectionSet(
//                                                [
//                                                    'id',
//                                                    'title',
//                                                ]
//                                            ),
//                                        ]
//                                    ),
//                                (new Query('settings'))->setSelectionSet(['status']),
//                            ]),
//                    ]
//                )
//            );

//        $results = (new GoodsModel())->getClient()->runQuery($basicQuery, true);

//        dump($results->getResults());die;

//        $this->model
//            ->selectFields($this->getSelectFields())
//            ->selectUk(['title', 'docket', 'promo_title_part'])
//            ->selectMpathCategories(['id', 'title', 'name'])
//            ->selectCategory(['id', 'title'])
//            ->selectProducer(['id', 'title'])
//            ->selectTags(['id', 'title', 'name', 'priority'])
//            ->selectAttachments(['url', 'order', 'variant', 'group_name'])
//            ->selectOptions(['option_id', 'values' => ['id'], 'type']);

//        $data = $this->model->getById($id);

//        if ($this->model->hasRemoteErrors()) {
//            return $this->model->getRemoteErrors();
//        }
//
//        $result = $this->getResult($data);
//        $this->reloadModel();
//
//        $this->model->selectOptions(['option_id', 'values' => ['id']]);
//
//        $data = $this->model->getById($id);
//
//        if ($this->model->hasRemoteErrors()) {
//            return $this->model->getRemoteErrors();
//        }
//
//        $result = array_merge($result, $this->getResult($data));

//        return $results->getResults();
    }
}
