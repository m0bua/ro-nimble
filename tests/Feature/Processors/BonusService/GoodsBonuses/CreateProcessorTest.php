<?php

namespace Tests\Feature\Processors\BonusService\GoodsBonuses;

use App\Cores\ConsumerCore\Message;
use App\Models\Eloquent\Bonus;
use App\Processors\BonusService\GoodsBonuses\CreateProcessor;
use Exception;
use Illuminate\Database\QueryException;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

class CreateProcessorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccessCreating()
    {
        $app = $this->createApplication();

        $body = [
            'timestamp' => 1614682831,
            'service' => 'program_loyalty',
            'entity' => 'goods_bonuses',
            'action' => 'create',
            'fields_data' => [
                'goods_id' => 304610,
                'pl_comment_bonus_charge' => 15,
                'pl_comment_photo_bonus_charge' => 0,
                'pl_comment_video_bonus_charge' => 0,
                'pl_bonus_not_allowed_pcs' => false,
                'pl_comment_video_child_bonus_charge' => 0,
                'pl_bonus_charge_pcs' => 0,
                'pl_use_instant_bonus' => false,
                'pl_premium_bonus_charge_pcs' => 0,
            ]
        ];

        $amqpMessage = new AMQPMessage(json_encode($body));
        $message = new Message($amqpMessage);

        $processor = $app->make(CreateProcessor::class);
        try {
            $processor->processMessage($message);
        } catch (QueryException $e) {
            if ($e->getCode() != 23505) {
                throw $e;
            }
        }

        $this->assertDatabaseHas(Bonus::class, [
            'goods_id' => 304610,
            'comment_bonus_charge' => 15,
            'comment_photo_bonus_charge' => 0,
            'comment_video_bonus_charge' => 0,
            'bonus_not_allowed_pcs' => 'false',
            'comment_video_child_bonus_charge' => 0,
            'bonus_charge_pcs' => 0,
            'use_instant_bonus' => 'false',
            'premium_bonus_charge_pcs' => 0,
        ], config('database.write'));
    }

    /**
     * When data fields are not included
     *
     * @throws Exception
     */
    public function testFailedCreating()
    {
        $app = $this->createApplication();
        $this->expectException(Exception::class);

        $body = [
            'timestamp' => 1614682831,
            'service' => 'program_loyalty',
            'entity' => 'goods_bonuses',
            'action' => 'create',
        ];

        $amqpMessage = new AMQPMessage(json_encode($body));
        $message = new Message($amqpMessage);

        $processor = $app->make(CreateProcessor::class);
        $processor->processMessage($message);
    }
}
