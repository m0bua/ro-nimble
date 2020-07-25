<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\ValueObjects\Message;
use App\ValueObjects\PromotionConstructor;
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
            $gqGoodsModel = new GraphQLGoodsModel();
            $elasticGoodsModel = new ElasticGoodsModel();
            $message = new Message($amqpMessage);

            $giftId = null;
            $promotionId = null;
            $goodsId = $message->getField('fields_data.goods_id');
            $constructorId = $message->getField('fields_data.promotion_constructor_id');

            $constructorInfo = json_decode(
                app('redis')->get($constructorId)
            );

            if ($constructorInfo !== null) {
                $promotionId = $constructorInfo->promotion_id;
                $giftId = $constructorInfo->gift_id;
            }

            $promotionConstructor = new PromotionConstructor(
                [
                    'id' => $constructorId,
                    'promotion_id' => $promotionId,
                    'gift_id' => $giftId,
                ]
            );

            $elasticGoodsModel->load($elasticGoodsModel->searchById($goodsId));
            $promotionConstructor->setSeats($elasticGoodsModel->getPromotionConstructors());
            $elasticGoodsModel->setPromotionConstructors($promotionConstructor->takeEmptySeat());
            $elasticGoodsModel->load($gqGoodsModel->getOneById($goodsId))->index();

            unset($gqGoodsModel, $elasticGoodsModel, $message);

        }, new RoutingKey($this->routingKey));
    }
}
