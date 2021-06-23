<?php

namespace Tests\Unit\Processors\BonusService\Support;

use App\Processors\BonusService\Support\ProcessorResolver;
use PHPUnit\Framework\TestCase;

class ProcessorResolverTest extends TestCase
{
    public function testValidRoutingKeyWithCtlAppendix()
    {
        $routingKey = 'change.goods_bonuses.CTL';

        $result = ProcessorResolver::resolve($routingKey);

        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeProcessor', $result);
    }

    public function testValidRoutingKeyWithAllAppendix()
    {
        $routingKey = 'change.goods_bonuses.ALL';

        $result = ProcessorResolver::resolve($routingKey);

        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeProcessor', $result);
    }

    public function testValidRoutingKeyWithoutAppendix()
    {
        $routingKey = 'change.goods_bonuses';

        $result = ProcessorResolver::resolve($routingKey);

        $this->assertEquals('BonusService\\GoodsBonuses\\ChangeProcessor', $result);
    }

    public function testNotValidRoutingKey()
    {
        $routingKey = 'change.goods_bonuses.';

        $result = ProcessorResolver::resolve($routingKey);

        $this->assertNotEquals('BonusService\\GoodsBonuses\\ChangeProcessor', $result);
    }
}
