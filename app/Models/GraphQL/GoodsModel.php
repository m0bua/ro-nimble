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
            'title',
            'price',
            'comments_amount',
            'sell_status',
            'seller_id',
            'merchant_id',
            'group_id',
            'state',
            'docket',
            'category_ids:mpath',
            'is_group_primary',
            'status',
            'status_inherited',
            'promo_title_part',
            'comments_mark',
            'goods_order:order',
            'series_id'
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
                (new Query('tags'))->setSelectionSet(['id', 'title', 'name', 'priority']),
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
            (new Query('attachments'))->setSelectionSet(['url', 'order']),
            (new Query('options'))->setSelectionSet([
                (new Query('settings'))->setSelectionSet(['status']),
                (new Query('details'))->setSelectionSet(['id', 'title', 'name', 'type']),
                (new InlineFragment('GoodsOptionSingle'))->setSelectionSet(['value']),
                (new InlineFragment('GoodsOptionPlural'))->setSelectionSet([
                    (new Query('values'))->setSelectionSet(['id', 'title']),
                ]),
            ]),
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
        return array_merge(
            $this->getGoodsOneData($goodsId, $this->getFirstPartParams()),
            $this->getGoodsOneData($goodsId, $this->getSecondPartParams())
        );
    }
}
