<?php

namespace Tests\Unit\Processors\MarketingService\Support;

use App\Processors\MarketingService\Support\LabelProcessorClassnameResolver;
use PHPUnit\Framework\TestCase;

class LabelProcessorClassnameResolverTest extends TestCase
{
    private LabelProcessorClassnameResolver $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new LabelProcessorClassnameResolver();
    }

    public function testItWillReturnUpsertProcessorNamespaceForCreate(): void
    {
        $this->assertEquals(
            'MarketingService\Labels\Label\UpsertEventProcessor',
            $this->resolver::resolve('create.label.all')
        );
    }

    public function testItWillReturnUpsertProcessorNamespaceForUpdate(): void
    {
        $this->assertEquals(
            'MarketingService\Labels\Label\UpsertEventProcessor',
            $this->resolver::resolve('update.label.all')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDelete(): void
    {
        $this->assertEquals(
            'MarketingService\Labels\Label\DeleteEventProcessor',
            $this->resolver::resolve('delete.label.all')
        );
    }

    public function testItWillReturnEmptyStringForInvalidRoutingKey(): void
    {
        $this->assertEmpty(
            $this->resolver::resolve('delete')
        );
        $this->assertEmpty(
            $this->resolver::resolve('delete.')
        );
    }

    public function testItWillReturnUpsertProcessorNamespaceForCreateEntityWithUnderscores(): void
    {
        $this->assertEquals(
            'MarketingService\Labels\LabelGoodsRelation\UpsertEventProcessor',
            $this->resolver::resolve('create.label_goods_relation.all')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDeleteEntityWithUnderscores(): void
    {
        $this->assertEquals(
            'MarketingService\Labels\LabelGoodsRelation\DeleteEventProcessor',
            $this->resolver::resolve('delete.label_goods_relation.all')
        );
    }
}
