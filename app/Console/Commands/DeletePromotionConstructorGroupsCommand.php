<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\Models\Elastic\Promotions\GoodsModel;
use App\ValueObjects\Message;
use App\ValueObjects\PromotionConstructor;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class DeletePromotionConstructorGroupsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:delete-promotion-constructor-groups';

    /**
     * @var string
     */
    protected $description = 'AMQP consumer for deleting promotion goods groups';

    /**
     * @var string
     */
    protected $routingKey = 'delete.Promotion_Constructor_Group.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $elasticGoodsModel = new GoodsModel();
            $message = new Message($amqpMessage);

            $groupGoodsData = $elasticGoodsModel->searchTermByField(
                'group_id',
                $message->getField('fields_data.group_id')
            );

            if (!empty($groupGoodsData)) {
                $constructorId = $message->getField('fields_data.promotion_constructor_id');

                array_map(function ($goodsOne) use ($constructorId, $elasticGoodsModel) {
                    $elasticGoodsModel->load($goodsOne);
                    $elasticGoodsModel->setPromotionConstructors(
                        PromotionConstructor::remove($constructorId, $elasticGoodsModel->getPromotionConstructors())
                    );

                    $elasticGoodsModel->index();
                }, $groupGoodsData);
            }

            unset($elasticGoodsModel, $message);

        }, new RoutingKey($this->routingKey));
    }
}
