<?php

namespace Tests\Feature\Processors\BonusService\GoodsBonuses;

use App\Cores\ConsumerCore\Message;
use App\Cores\Shared\Codes;
use App\Processors\BonusService\GoodsBonuses\ChangeProcessor;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\CreatesApplication;
use Tests\TestCase;

class ChangeProcessorTest extends TestCase
{
    use CreatesApplication;

    /**
     * @throws Exception
     */
    public function testSuccessUpdating()
    {
        $app = $this->createApplication();

        $body = [
            'timestamp' => 1614682831,
            'service' => 'program_loyalty',
            'entity' => 'goods_bonuses',
            'action' => 'change',
            'fields_data' => [
                'goods_id' => 304610,
                'pl_comment_bonus_charge' => 15,
                'pl_comment_photo_bonus_charge' => 0,
                'pl_comment_video_bonus_charge' => 0,
                'pl_bonus_not_allowed_pcs' => false,
                'pl_comment_video_child_bonus_charge' => 0,
                'pl_bonus_charge_pcs' => 0,
                'pl_use_instant_bonus' => false,
                'pl_premium_bonus_charge_pcs' => 0
            ]
        ];

        $amqpMessage = new AMQPMessage(json_encode($body));
        $message = new Message($amqpMessage);

        $processor = $app->make(ChangeProcessor::class);
        $result = $processor->processMessage($message);

        $this->assertEquals(Codes::SUCCESS, $result);
    }

    /**
     * When data fields are not included
     *
     * @throws Exception
     */
    public function testFailedUpdating()
    {
        $app = $this->createApplication();

        $this->expectException(Exception::class);

        $body = [
            'timestamp' => 1614682831,
            'service' => 'program_loyalty',
            'entity' => 'goods_bonuses',
            'action' => 'change',
        ];

        $amqpMessage = new AMQPMessage(json_encode($body));
        $message = new Message($amqpMessage);

        $processor = $app->make(ChangeProcessor::class);
        $processor->processMessage($message);
    }
}
