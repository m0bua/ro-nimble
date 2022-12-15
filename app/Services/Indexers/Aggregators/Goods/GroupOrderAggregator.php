<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Interfaces\GroupsBuffer;
use App\Components\ElasticSearchComponents\SortComponents\{
    CheapComponent, ExpensiveComponent, RankComponent
};
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupOrderAggregator extends AbstractAggregator
{
    /**
     * @var GroupsBuffer
     */
    private GroupsBuffer $redisGroupsBuffer;

    private const ORDER_CHEAP = 0;
    private const ORDER_EXPENSIVE = 1;
    private const ORDER_RANK = 2;
    private const ORDERS = [
        'cheap'     => self::ORDER_CHEAP,
        'expensive' => self::ORDER_EXPENSIVE,
        'rank'      => self::ORDER_RANK,
    ];

    public function __construct(GroupsBuffer $redisGroupsBuffer)
    {
        $this->redisGroupsBuffer = $redisGroupsBuffer;
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        $return = [];
        $idGroupId = DB::table('goods as g')
            ->select(['id', 'group_id'])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id')
            ->map(function ($item) {
                return $item->group_id;
            });

        foreach ($idGroupId as $productId => $groupId) {
            if (0 === $groupId) {
                $return[$productId] = [];
                continue;
            }

            $order = [];
            $redisOrder = !$this->isPartial
                ? $this->readOrderFromRedis($groupId, $productId)
                : [];

            if (empty($redisOrder)) {
                $group = DB::table('goods as g')
                    ->select([
                        'g.price',
                        'g.id',
                        'g.sell_status',
                        'g.group_id',
                        'g.is_group_primary',
                        'g.rank',
                        'g.order',
                        'g.state',
                        'g.seller_id',
                        'c.is_rozetka_top'
                    ])
                    ->join('categories as c', 'g.category_id', 'c.id')
                    ->where('group_id', '=', $groupId)
                    ->get();

                $orderArray = $this->prepareOrderArray([
                    'cheap'     => CheapComponent::getOrderInGroup($group),
                    'expensive' => ExpensiveComponent::getOrderInGroup($group),
                    'rank'      => RankComponent::getOrderInGroup($group)
                ]);
                foreach ($orderArray as $orderProductId => $productOrder) {
                    if (!$this->isPartial) {
                        $this->redisGroupsBuffer
                            ->addProduct($groupId, $orderProductId, $this->prepareOrderToRedis($productOrder));
                    }
                    if ($productId === $orderProductId) {
                        $order = $productOrder;
                    }
                }
            } else {
                $order = $redisOrder;
            }

            $return[$productId] = $order;
        }

        return collect($return);
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->group_order = $this->get($item->id);

        return $item;
    }

    /**
     * Prepares sort data
     *
     * @param $orderArrays
     * @return array
     */
    private function prepareOrderArray($orderArrays): array
    {
        $return = [];
        foreach ($orderArrays as $key => $group) {
            foreach ($group as $order => $product) {
                $return[$product['id']][] = ['sort' => $key, 'order' => $order];
            }
        }

        return $return;
    }

    /**
     * Prepares order data to save in redis
     *
     * @param array $productOrder
     * @return array
     */
    private function prepareOrderToRedis(array $productOrder): array
    {
        $return = [];
        if (empty($productOrder)) {
            return $return;
        }
        foreach ($productOrder as $orderType) {
            $return[self::ORDERS[$orderType['sort']]] = $orderType['order'];
        }

        return $return;
    }

    /**
     * Reads order data from redis
     *
     * @param int $groupId
     * @param int $productId
     * @return array
     */
    private function readOrderFromRedis(int $groupId, int $productId): array
    {
        $return = [];
        $order = $this->redisGroupsBuffer->getGroupOrder($groupId, $productId);
        if (null === $order) {
            return $return;
        }

        $ordersFlip = \array_flip(self::ORDERS);
        foreach (\json_decode($order) as $orderKey => $orderValue) {
            $return[] = [
                'sort'  => $ordersFlip[$orderKey],
                'order' => $orderValue,
            ];
        }

        return $return;
    }
}
