<?php

namespace Tests\Unit\Cores\ConsumerCore;

use App\Cores\ConsumerCore\Message;
use Exception;
use JsonException;
use Mockery\MockInterface;
use PhpAmqpLib\Message\AMQPMessage;
use RuntimeException;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * @param string $body
     * @param string $routingKey
     * @return MockInterface|AMQPMessage
     */
    private function mockAmqpMessage(string $body, string $routingKey = '')
    {
        /** @var MockInterface|AMQPMessage $mock */
        $mock = $this->partialMock(AMQPMessage::class);
        $mock->setBody($body);
        $mock->setDeliveryInfo(1, false, 'test', $routingKey);

        return $mock;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetMessage(): void
    {
        $body = json_encode([
            'a' => 1
        ], JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock);

        $this->assertJsonStringEqualsJsonString($body, $message->getMessage());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetBodyAsArray(): void
    {
        $arrayBody = [
            'a' => 1
        ];
        $body = json_encode($arrayBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, true);

        $this->assertEquals($arrayBody, $message->getBody());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetBodyAsObject(): void
    {
        $objectBody = (object)[
            'a' => 1
        ];
        $body = json_encode($objectBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->assertEquals($objectBody, $message->getBody());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetBodyAsScalarValue(): void
    {
        $body = json_encode('test string', JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, true);

        $this->assertEquals('test string', $message->getBody());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetRoutingKey(): void
    {
        $body = json_encode([
            'a' => 1
        ], JSON_THROW_ON_ERROR);
        $routingKey = 'test.routing.key';
        $mock = $this->mockAmqpMessage($body, $routingKey);
        $message = new Message($mock);

        $this->assertEquals($routingKey, $message->getRoutingKey());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetScalarFieldFromObject(): void
    {
        $arrayBody = [
            'a' => 1
        ];
        $body = json_encode($arrayBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->assertEquals(1, $message->getField('a'));
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetObjectFieldFromObject(): void
    {
        $object = (object)[
            'b' => 2,
            'c' => 3
        ];
        $arrayBody = [
            'a' => $object
        ];
        $body = json_encode($arrayBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->assertEquals($object, $message->getField('a'));
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetArrayFieldFromObject(): void
    {
        $arr = [2, 'string', true, 3.14, []];
        $arrayBody = [
            'a' => $arr
        ];
        $body = json_encode($arrayBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->assertEquals($arr, $message->getField('a'));
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testGetNestedField(): void
    {
        $arrayBody = [
            'a' => [
                'b' => [
                    'c' => 1
                ],
            ],
        ];
        $body = json_encode($arrayBody, JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->assertEquals(1, $message->getField('a.b.c'));
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testItWillThrowExceptionOnUndefinedField(): void
    {
        $body = json_encode(['a' => 1], JSON_THROW_ON_ERROR);
        $mock = $this->mockAmqpMessage($body);
        $message = new Message($mock, false);

        $this->expectException(RuntimeException::class);
        $message->getField('b');
    }

    /**
     * @throws Exception
     */
    public function testItWillThrowErrorOnInvalidJson(): void
    {
        $invalidBody = '{"a:0}';
        $mock = $this->mockAmqpMessage($invalidBody);

        $this->expectException(RuntimeException::class);
        new Message($mock);
    }
}
