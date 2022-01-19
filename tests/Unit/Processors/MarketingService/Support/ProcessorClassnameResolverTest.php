<?php

namespace Tests\Unit\Processors\MarketingService\Support;

use App\Processors\MarketingService\Support\ProcessorClassnameResolver;
use PHPUnit\Framework\TestCase;

class ProcessorClassnameResolverTest extends TestCase
{
    private ProcessorClassnameResolver $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new ProcessorClassnameResolver();
    }

    public function testItWillReturnUpsertProcessorNamespaceForUpdateConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructor\UpsertEventProcessor',
            $this->resolver::resolve('change.Promotion_Constructor.record')
        );
    }

    public function testItWillReturnUpsertProcessorNamespaceForUpdateGoodsConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructorGoods\UpsertEventProcessor',
            $this->resolver::resolve('change.Promotion_Constructor_Goods.record')
        );
    }

    public function testItWillReturnUpsertProcessorNamespaceForUpdateGroupConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructorGroup\UpsertEventProcessor',
            $this->resolver::resolve('change.Promotion_Constructor_Group.record')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDeleteConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructor\DeleteEventProcessor',
            $this->resolver::resolve('delete.Promotion_Constructor.record')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDeleteGoodsConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructorGoods\DeleteEventProcessor',
            $this->resolver::resolve('delete.Promotion_Constructor_Goods.record')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDeleteGroupConstructor(): void
    {
        $this->assertEquals(
            'MarketingService\PromotionConstructorGroup\DeleteEventProcessor',
            $this->resolver::resolve('delete.Promotion_Constructor_Group.record')
        );
    }
}
