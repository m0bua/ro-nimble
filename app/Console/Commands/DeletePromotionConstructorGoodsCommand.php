<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\Models\Elastic\Promotions\GoodsModel;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class DeletePromotionConstructorGoodsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:delete-promotion-constructor-goods';

    /**
     * @var string
     */
    protected $description = 'AMQP consumer for deleting promotion goods';

    /**
     * @var string
     */
    protected $routingKey = 'delete.Promotion_Constructor_Goods.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $elasticGoodsModel = new GoodsModel();
            $message = new Message($amqpMessage);

            $goodsData = $elasticGoodsModel->searchById(
                $message->getField('fields_data.goods_id')
            );

            if (!empty($goodsData)) {
                $constructorId = $message->getField('fields_data.promotion_constructor_id');

                $elasticGoodsModel->load($goodsData);
                $elasticGoodsModel->setPromotionConstructors(
                    $this->removeConstructor($constructorId, $elasticGoodsModel->getPromotionConstructors())
                );

                $elasticGoodsModel->index();
            }

            unset($elasticGoodsModel, $message);
        }, new RoutingKey($this->routingKey));
    }

    /**
     * @param int $constructorId
     * @param array $constructors
     * @return array[]
     */
    private function removeConstructor(int $constructorId, array $constructors): array
    {
        foreach ($constructors as $key => $constructor)
        {
            if ($constructor['id'] === $constructorId) {
                unset($constructors[$key]);
            }
        }

        return $constructors;
    }
}
