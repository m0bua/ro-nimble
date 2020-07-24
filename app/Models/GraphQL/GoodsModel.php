<?php

namespace App\Models\GraphQL;

use App\ValueObjects\Options;
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
    public function getParams()
    {
        return array_merge(
            $this->mainFieldsStack(), [
                (new Query('producer'))->setSelectionSet(['producer_id:id', 'producer_name:name']),
                (new Query('tags'))->setSelectionSet(['id']),
//                (new Query('goods_ranks'))->setSelectionSet(['rank:search_rank', 'income_order:search_rank']),
                (new Query('options'))->setSelectionSet([
                    (new InlineFragment('GoodsOptionSingle'))->setSelectionSet([
                        'value',
                        (new Query('details'))->setSelectionSet([
                            'id', 'name', 'type', 'state',
                        ]),
                    ]),
                    (new InlineFragment('GoodsOptionPlural'))->setSelectionSet([
                        (new Query('details'))->setSelectionSet([
                            'id', 'name', 'type', 'state',
                            (new Query('values'))->setSelectionSet([
                                'id', 'name', 'status'
                            ]),
                        ]),
                    ]),
                ]),
            ]
        );
    }

    /**
     * Преобразует данные о товаре к правильному виду
     *
     * @param $data
     * @return array
     */
    public function formatResponse($data)
    {
        $options = new Options($data['options']);

        $data['category_ids'] = array_filter(explode('.', $data['category_ids']));
        $data['seller_order'] = $data['seller_id'] == 5 ? 1 : 0;
        $data['tags'] = array_column($data['tags'], 'id');
        $data = array_merge($data, $data['producer']);
//        $data = array_merge($data, $data['goods_ranks']);

        unset($data['options'], $data['producer'], $data['goods_ranks']);

        return array_merge($data, $options->getOptions());
    }

    /**
     * Получение отформатированных данных одного товара по id
     *
     * @param int $id
     * @return array
     */
    public function getOneById(int $id): array
    {
        return $this->formatResponse($this->getGoodsOneData($id, $this->getParams()));
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
        $result = $this->getGroupGoodsData($groupId, $this->getParams());

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
