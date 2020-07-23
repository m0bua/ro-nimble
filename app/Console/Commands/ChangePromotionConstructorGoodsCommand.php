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
            $gqGoodsModel = new GraphQLGoodsModel();
            $elasticGoodsModel = new ElasticGoodsModel();
            $message = new Message($amqpMessage);

            $goodsId = $message->getField('fields_data.goods_id');
            $constructorId = $message->getField('fields_data.promotion_constructor_id');
            $promotionId = null;
            $giftId = null;

            $constructorInfo = json_decode(
                app('redis')->get($constructorId)
            );

            if ($constructorInfo !== null) {
                $promotionId = $constructorInfo->promotion_id;
                $giftId = $constructorInfo->gift_id;
            }

            $elasticGoodsModel->load($elasticGoodsModel->searchById($goodsId));
            $elasticGoodsModel->setPromotionConstructors(
                $this->takeEmptySeat(
                    $elasticGoodsModel->getPromotionConstructors(),
                    [
                        'id' => $constructorId,
                        'promotion_id' => $promotionId,
                        'gift_id' => $giftId,
                    ]
                )
            );
            $elasticGoodsModel->load($gqGoodsModel->getOneById($goodsId))->index();

            unset($gqGoodsModel, $elasticGoodsModel, $message);

        }, new RoutingKey($this->routingKey));
    }

    /**
     * @param array $seats
     * @param array $body
     * @return array[]
     */
    public function takeEmptySeat(array $seats, array $body): array
    {
        $seatTaken = false;
        foreach ($seats as &$seat) {
            if ($seat['id'] === $body['id'] && $seat['promotion_id'] === null) {
                $seat['promotion_id'] = $body['promotion_id'];
                $seat['gift_id'] = $body['gift_id'];
                $seatTaken = true;
                break;
            }
        }
        if (!$seatTaken) {
            array_push($seats, $body);
        }

        return array_unique($seats, SORT_REGULAR);
    }
}
