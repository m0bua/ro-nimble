<?php

namespace Tests\Unit\Processors\GoodsService\Support;

use App\Processors\GoodsService\Support\ProcessorClassnameResolver;
use Tests\TestCase;

class ProcessorClassnameResolverTest extends TestCase
{
    private ProcessorClassnameResolver $resolver;

    public function testItWillReturnNamespaceForEntity(): void
    {
        $routingKey = 'create.category.entity';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\\CreateCategoryEntityProcessor', $result);
    }

    public function testItWillReturnNamespaceForTranslation(): void
    {
        $routingKey = 'create.option.value_uk';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\\Translations\\CreateOptionValueUkProcessor', $result);
    }

    public function testItCannotReturnNamespaceForInvalidRoutingKey(): void
    {
        $routingKey = 'create.producer.';

        $result = $this->resolver->resolve($routingKey);

        $this->assertNotEquals('GoodsService\\CreateProducerEntityProcessor', $result);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new ProcessorClassnameResolver();
    }
}
