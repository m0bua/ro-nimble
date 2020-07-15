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
                (new Query('tags'))->setSelectionSet(['id']),
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
     * Преобразует данные о товаре к правильному виду
     *
     * @param $data
     * @return array
     */
    public function formatResponse($data)
    {
        $data['seller_order'] = $data['seller_id'] == 5 ? 1 : 0;
        $data['tags'] = implode(',', array_column($data['tags'], 'id'));
        $data = array_merge($data, $data['producer']);
        unset($data['producer']);
//        $data = array_merge($data, $data['goods_ranks']);
//        unset($data['goods_ranks']);

        return $data;
    }

    /**
     * Получение отформатированных данных одного товара по id
     *
     * @param int $id
     * @return array
     */
    public function getOneById(int $id): array
    {
        return $this->formatResponse(array_merge(
            $this->getGoodsOneData($id, $this->getFirstPartParams())
//            , $this->getGoodsOneData($id, $this->getSecondPartParams())
        ));
    }

    /**
     * Получение одного товара по id
     *
     * @param int $goodsId
     * @param array $fields
     * @return array
     */
    public function getGoodsOneData(int $id, array $fields): array
    {
        return $this->client
            ->runQuery($this->getGoodsOneQuery($id, $fields),true)
            ->getResults()['data']['goodsOne'];
    }

    /**
     * Генерирует запрос на получение одного товара по id
     *
     * @param int $id
     * @param array $fields
     * @return Query
     */
    private function getGoodsOneQuery(int $id, array $fields): Query
    {
        return (new Query('goodsOne'))
            ->setArguments(['where' => new RawObject("{id_eq: $id}")])
            ->setSelectionSet($fields);
    }

    /**
     * Получение отформатированных данных товаров одной группы
     *
     * @param int $groupId
     * @return array
     */
    public function getManyByGroup(int $groupId): array
    {
        $result = array_merge(
            $this->getGroupGoodsData($groupId, $this->getFirstPartParams())
//            , $this->getGoodsOneData($id, $this->getSecondPartParams())
        );

        foreach ($result as &$goods) {
            $goods = $this->formatResponse($goods);
        }

        return $result;
    }

    /**
     * Получение товаров одной группы
     *
     * @param int $groupId
     * @param array $fields
     * @return array
     */
    public function getGroupGoodsData(int $groupId, array $fields): array
    {
        return $this->client
            ->runQuery($this->getGroupGoodsQuery($groupId, $fields),true)
            ->getResults()['data']['goodsMany']['nodes'];
    }


    /**
     * Генерирует запрос на получение одного товара по id
     *
     * @param int $groupId
     * @param array $fields
     * @return Query
     */
    private function getGroupGoodsQuery(int $groupId, array $fields): Query
    {
        return (new Query('goodsMany'))
            ->setArguments(['where' => new RawObject("{group_id_eq: $groupId}")])
            ->setSelectionSet([
                (new Query('nodes'))->setSelectionSet($fields)
            ]);
    }
}
