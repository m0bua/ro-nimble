<?php

namespace Tests\Unit\Processors\PaymentService\Support;

use App\Processors\PaymentService\Support\ProcessorClassnameResolver;
use PHPUnit\Framework\TestCase;

class ProcessorNamespaceResolverTest extends TestCase
{
    /**
     * @test
     */
    public function testItWillReturnNamespaceIfRoutingKeyWithPrefix()
    {
        $routingKey = 'changed.credits_goods.all';
        $result = ProcessorClassnameResolver::resolve($routingKey);
        $this->assertEquals('PaymentService\\CreditsGoods\\ChangedEventProcessor', $result);
    }

    /**
     * @test
     */
    public function testItWillReturnNamespaceIfRoutingKeyWithoutPrefix()
    {
        $routingKey = 'changed.credits_goods';
        $result = ProcessorClassnameResolver::resolve($routingKey);
        $this->assertEquals('PaymentService\\CreditsGoods\\ChangedEventProcessor', $result);
    }

    /**
     * @test
     */
    public function testItCannotReturnNamespaceForInvalidRoutingKey()
    {
        $routingKey = 'changed.credits_goods.';

        $result = ProcessorClassnameResolver::resolve($routingKey);

        $this->assertNotEquals('PaymentService\\CreditsGoods\\ChangedEventProcessor', $result);
    }
}
