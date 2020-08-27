<?php

namespace App\Models\GraphQL;

use App\Interfaces\OptionsInterface;
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
     * @var array
     */
    private $selection = [];

    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * GoodsModel constructor.
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options)
    {
        $this->options = $options;

        $this->prepareSelection();

        parent::__construct();
    }

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
            'mpath',
            'price',
            'sell_status',
            'seller_id',
            'group_id',
            'is_group_primary',
            'status_inherited',
            'order',
            'series_id',
            'state',
            'pl_bonus_charge_pcs',
        ];
    }

    /**
     * Получение отформатированных данных одного товара по id
     *
     * @param int $id
     * @return array
     */
    public function getOneById(int $id): array
    {
        echo $id . "\n";
        return $this->formatResponse(
            $this->getGoodsOne(new RawObject("{id_eq: $id}"))
        );
    }

    /**
     * Получение отформатированных данных товаров одной группы
     *
     * @param int $groupId
     * @return array
     */
    public function getManyByGroup(int $groupId): array
    {
        $result = $this->getGoodsMany(new RawObject("{group_id_eq: $groupId}"));

        return array_map(function ($goods) {
            return $this->formatResponse($goods);
        }, $result);
    }

    /**
     * @param $where
     * @return array
     */
    public function getGoodsOne($where): array
    {
        $query = (new Query('goodsOne'))
            ->setArguments(['where' => $where])
            ->setSelectionSet($this->selection);

        return $this->client
            ->runQuery($query,true)
            ->getResults()['data']['goodsOne'];
    }

    /**
     * @param $where
     * @return array
     */
    public function getGoodsMany($where): array
    {
        $query = (new Query('goodsMany'))
            ->setArguments(['where' => $where])
            ->setSelectionSet([
                (new Query('nodes'))->setSelectionSet($this->selection)
            ]);

        return $this->client
            ->runQuery($query,true)
            ->getResults()['data']['goodsMany']['nodes'];
    }

    /**
     * Prepare selection set
     */
    private function prepareSelection()
    {
        $this->selection = array_merge(
            $this->mainFieldsStack(),
            [
                (new Query('producer'))->setSelectionSet(['producer_id:id', 'producer_name:name']),
                (new Query('tags'))->setSelectionSet(['id']),
                (new Query('rank'))->setSelectionSet(['search_rank']),
                (new Query('options'))->setSelectionSet([
                    (new InlineFragment('GoodsOptionSingle'))->setSelectionSet([
                        'value',
                        (new Query('details'))->setSelectionSet(['id', 'name', 'type', 'state']),
                    ]),
                    (new InlineFragment('GoodsOptionPlural'))->setSelectionSet([
                        (new Query('details'))->setSelectionSet(['id', 'name', 'type', 'state']),
                        (new Query('values'))->setSelectionSet(['id', 'name', 'status']),
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
    private function formatResponse($data)
    {
        $this->options->fill($data['options']);

        if (isset($data['mpath'])) {
            $data['categories_path'] = array_map('intval', array_values(array_filter(explode('.', $data['mpath']))));
        }

        if (isset($data['seller_id'])) {
            $data['seller_order'] = $data['seller_id'] == 5 ? 1 : 0;
        }

        if (isset($data['tags'])) {
            $data['tags'] = array_column($data['tags'], 'id');
        }

        if (isset($data['producer'])) {
            $data = array_merge($data, $data['producer']);
        }

        if (isset($data['rank'])) {
            $data = array_merge($data, $data['rank']);
        }

        unset($data['options'], $data['producer'], $data['rank'], $data['mpath']);

        return array_merge($data, $this->options->get());
    }
}
