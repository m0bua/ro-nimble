<?php

namespace App\Models\GraphQL;

use App\Library\Services\GraphQL;
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
     * @param int $goodsId
     * @return array
     */
    public function getOneById(int $goodsId): array
    {
        return $this->client->runQuery(
            $this->goodsOneCommonInfoQuery($goodsId),
            true
        )->getResults()['data']['goodsOne'];
    }

    /**
     * @param int $goodsId
     * @return Query
     */
    private function goodsOneCommonInfoQuery(int $goodsId): Query
    {
        return (new Query('goodsOne'))
            ->setArguments(['where' => new RawObject("{id_eq: $goodsId}")])
            ->setSelectionSet(
                array_merge(
                    $this->mainFieldsStack(),
                    [
                        (new Query('options'))
                            ->setSelectionSet([
                                (new Query('details'))
                                    ->setSelectionSet([
                                        'id',
                                        'title',
                                        'name',
                                        'type',
                                    ]),
                                (new InlineFragment('GoodsOptionSingle'))->setSelectionSet(['value']),
                                (new InlineFragment('GoodsOptionPlural'))
                                    ->setSelectionSet(
                                        [
                                            (new Query('values'))
                                                ->setSelectionSet(
                                                    [
                                                        'id',
                                                        'title',
                                                    ]
                                                ),
                                        ]
                                    ),
                                (new Query('settings'))->setSelectionSet(['status']),
                            ]),
                    ]
                )
            );
    }
}
