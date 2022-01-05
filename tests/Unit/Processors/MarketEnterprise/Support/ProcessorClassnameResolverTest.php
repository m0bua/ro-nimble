<?php

namespace Tests\Unit\Processors\MarketEnterprise\Support;

use App\Processors\MarketEnterprise\Support\ProcessorClassnameResolver;
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

    public function testItWillReturnUpsertProcessorNamespaceForCreate(): void
    {
        $this->assertEquals(
            'MarketEnterprise\ProductsType\UpsertEventProcessor',
            $this->resolver::resolve('create.products.type.SLF')
        );
        $this->assertEquals(
            'MarketEnterprise\ProductsType\UpsertEventProcessor',
            $this->resolver::resolve('create.products.type')
        );
    }

    public function testItWillReturnUpsertProcessorNamespaceForUpdate(): void
    {
        $this->assertEquals(
            'MarketEnterprise\ProductsType\UpsertEventProcessor',
            $this->resolver::resolve('update.products.type.SLF')
        );
        $this->assertEquals(
            'MarketEnterprise\ProductsType\UpsertEventProcessor',
            $this->resolver::resolve('update.products.type')
        );
    }

    public function testItWillReturnDeleteProcessorNamespaceForDelete(): void
    {
        $this->assertEquals(
            'MarketEnterprise\ProductsType\DeleteEventProcessor',
            $this->resolver::resolve('delete.products.type.SLF')
        );
        $this->assertEquals(
            'MarketEnterprise\ProductsType\DeleteEventProcessor',
            $this->resolver::resolve('delete.products.type')
        );
    }

    public function testItWillReturnEmptyStringForInvalidRoutingKey(): void
    {
        $this->assertEmpty(
            $this->resolver::resolve('delete.products')
        );
        $this->assertEmpty(
            $this->resolver::resolve('delete.products.')
        );
    }
}
