<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Cores\ConsumerCore\Message;
use App\Models\Eloquent\PromotionConstructor;
use App\Processors\MarketingService\ChangePromotionConstructorProcessor;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

class ChangePromotionConstructorProcessorTest extends TestCase
{
    protected ChangePromotionConstructorProcessor $processor;

    protected array $data = [
        'fields_data' => [
            'id' => 1,
            'promotion_id' => 1,
            'gift_id' => 1,
        ],
    ];

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = $this->app->make(ChangePromotionConstructorProcessor::class);
        $this->cleanDatabase();
    }

    protected function tearDown(): void
    {
        $this->cleanDatabase();
        parent::tearDown();
    }

    /**
     * @return Message
     * @throws Exception
     */
    protected function prepareMessage(): Message
    {
        $amqpMessage = new AMQPMessage(json_encode($this->data));
        return new Message($amqpMessage);
    }

    protected function cleanDatabase(): void
    {
        PromotionConstructor::whereId($this->data['fields_data']['id'])->delete();
    }

    /**
     * @test
     * @throws Exception
     */
    public function testItCanCreateRecord()
    {
        $this->assertDatabaseMissing(PromotionConstructor::class, $this->data['fields_data']);

        $message = $this->prepareMessage();
        $this->processor->processMessage($message);

        $this->assertDatabaseHas(PromotionConstructor::class, $this->data['fields_data']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function testItCanUpdateRecord()
    {
        PromotionConstructor::create($this->data['fields_data']);

        $message = $this->prepareMessage();
        $this->processor->processMessage($message);

        $this->assertDatabaseHas(PromotionConstructor::class, $this->data['fields_data']);
    }
}
