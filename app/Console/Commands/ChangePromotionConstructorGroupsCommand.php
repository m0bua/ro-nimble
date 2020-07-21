<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class ChangePromotionConstructorGroupsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "consumer:change-promotion-constructor-groups";

    /**
     * @var string
     */
    protected $description = "AMQP Consumer for creating or changing promotion goods groups";

    /**
     * @var string
     */
    protected $routingKey = 'change.Promotion_Constructor_Group.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($message, $resolver) {
            // TODO

            $groupId = 4353543534;
            $promotionId = 123;
            $constructorId = 321;
            $giftId = 333;

            $gqGoodsModel = new GraphQLGoodsModel();
            $elasticGoodsModel = new ElasticGoodsModel();

            $elasticGoodsModel->load(
                array_merge(
                    $gqGoodsModel->getManyByGroup($groupId),
                    [
                        'promotion_id' => $promotionId,
                        'constructor_id' => $constructorId,
                        'gift_id' => $giftId
                    ]
                )
            )->index();

            var_dump($message->body);
        }, new RoutingKey($this->routingKey));
    }
}
