<?php

namespace Tests\Unit\Processors\BonusService\Support;

use App\Processors\BonusService\Support\ProcessorClassnameResolver;
use PHPUnit\Framework\TestCase;

class ProcessorNamespaceResolverTest extends TestCase
{
    public function testItWillReturnNamespaceIfRoutingKeyWithPrefix(): void
    {
        $routingKey = 'change.goods_bonuses.CTL';
        $result = ProcessorClassnameResolver::resolve($routingKey);
        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeEventProcessor', $result);

        $routingKey = 'change.goods_bonuses.ALL';
        $result = ProcessorClassnameResolver::resolve($routingKey);
        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeEventProcessor', $result);
    }

    public function testItWillReturnNamespaceIfRoutingKeyWithoutPrefix(): void
    {
        $routingKey = 'change.goods_bonuses';

        $result = ProcessorClassnameResolver::resolve($routingKey);

        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeEventProcessor', $result);
    }

    public function testItCannotReturnNamespaceForInvalidRoutingKey(): void
    {
        $routingKey = 'change.goods_bonuses.';

        $result = ProcessorClassnameResolver::resolve($routingKey);

        $this->assertNotEquals('BonusService\\GoodsBonuses\\ChangeEventProcessor', $result);
    }
}
