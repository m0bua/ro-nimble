<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;

use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class ChangePromotionConstructorGoodsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "consumer:change-promotion-constructor-goods";

    /**
     * @var string
     */
    protected $description = "AMQP Consumer for creating or changing promotion goods";

    /**
     * @var string
     */
    protected $routingKey = 'change.Promotion_Constructor_Goods.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $message = new Message($amqpMessage);
            $goodsId = $message->getField('fields_data.goods_id');
            $constructorId = $message->getField('fields_data.promotion_constructor_id');
            $constructorInfo = json_decode(
                app('redis')->get($constructorId)
            );

            if ($constructorInfo !== null) {
                $gqGoodsModel = new GraphQLGoodsModel();
                $elasticGoodsModel = new ElasticGoodsModel();

                $elasticGoodsModel->load($elasticGoodsModel->searchById($goodsId));

                $elasticGoodsModel->setPromotionConstructors(
                    array_unique(
                        array_merge(
                            $elasticGoodsModel->getPromotionConstructors(),
                            [[
                                'id' => $constructorId,
                                'promotion_id' => $constructorInfo->promotion_id,
                                'gift_id' => $constructorInfo->gift_id,
                            ]]
                        ),
                        SORT_REGULAR
                    )
                );

                $elasticGoodsModel->load($gqGoodsModel->getOneById($goodsId))->index();
            }
        }, new RoutingKey($this->routingKey));
    }
}
