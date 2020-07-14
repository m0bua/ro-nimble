<?php

namespace App\Models\GraphQL;

use GraphQL\InlineFragment;
use GraphQL\Query;
use GraphQL\RawObject;

/**
 * Class GoodsModel
 * @package App\Models\GraphQL
 */
class GoodsModel extends GraphQL
{
    /**
     * @inheritDoc
     */
    public function serviceName(): string
    {
        return 'goods';
    }

    /**
     * @return string[]
     */
    public function mainFieldsStack()
    {
        return [
            'id',
            'category_id',
            'category_ids:mpath',
            'price',
            'sell_status',
            'seller_id',
            'group_id',
            'is_group_primary',
            'status_inherited',
            'goods_order:order',
            'series_id',
            'state',
//            'bonus_charge:pl_bonus_charge_pcs',
        ];
    }

    /**
     * first part of the parameters
     *
     * @return array
     */
    public function getFirstPartParams()
    {
        return array_merge(
            $this->mainFieldsStack(), [
                (new Query('producer'))->setSelectionSet(['producer_id:id', 'producer_name:name']),
//                (new Query('goods_ranks'))->setSelectionSet(['rank:search_rank', 'income_order:search_rank']),
            ]
        );
    }

    /**
     * second part of the parameters
     *
     * @return array
     */
    public function getSecondPartParams()
    {
        return [
//            (new Query('attachments'))->setSelectionSet(['url', 'order']),
//            (new Query('options'))->setSelectionSet([
//                (new Query('settings'))->setSelectionSet(['status']),
//                (new Query('details'))->setSelectionSet(['id', 'title', 'name', 'type']),
//                (new InlineFragment('GoodsOptionSingle'))->setSelectionSet(['value']),
//                (new InlineFragment('GoodsOptionPlural'))->setSelectionSet([
//                    (new Query('values'))->setSelectionSet(['id', 'title']),
//                ]),
//            ]),
        ];
    }

    /**
     * @param int $goodsId
     * @param array $fields
     * @return Query
     */
    private function goodsOneCommonInfoQuery(int $goodsId, array $fields): Query
    {
        return (new Query('goodsOne'))
            ->setArguments(['where' => new RawObject("{id_eq: $goodsId}")])
            ->setSelectionSet($fields);
    }

    /**
     * @param int $goodsId
     * @param array $fields
     * @return array
     */
    public function getGoodsOneData(int $goodsId, array $fields): array
    {
        return $this->client->runQuery(
            $this->goodsOneCommonInfoQuery($goodsId, $fields),
            true
        )->getResults()['data']['goodsOne'];
    }

    /**
     * @param int $goodsId
     * @return array
     */
    public function getOneById(int $goodsId): array
    {
        return $this->getResult(array_merge(
            $this->getGoodsOneData($goodsId, $this->getFirstPartParams())
//            ,
//            $this->getGoodsOneData($goodsId, $this->getSecondPartParams())
        ));
    }

    public function getResult($data)
    {
        $data['seller_order'] = $data['seller_id'] == 5 ? 1 : 0;
        $data = array_merge($data, $data['producer']);
        unset($data['producer']);

//        $data = array_merge($data, $data['goods_ranks']);
//        unset($data['goods_ranks']);

        return $data;
    }
}
