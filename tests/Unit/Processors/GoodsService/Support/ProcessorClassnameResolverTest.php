<?php

namespace Tests\Unit\Processors\GoodsService\Support;

use App\Processors\GoodsService\Support\ProcessorClassnameResolver;
use Tests\TestCase;

class ProcessorClassnameResolverTest extends TestCase
{
    private ProcessorClassnameResolver $resolver;

    public function testItWillReturnUpsertProcessorForCreateEntity(): void
    {
        $routingKey = 'create.category.entity';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Category\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForUpdateEntity(): void
    {
        $routingKey = 'change.category.entity';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Category\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForSyncEntity(): void
    {
        $routingKey = 'sync.category.entity';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Category\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForCreateRelation(): void
    {
        $routingKey = 'create.goods.option_plural';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\GoodsOptionPlural\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForUpdateRelation(): void
    {
        $routingKey = 'change.goods.option_plural';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\GoodsOptionPlural\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForSyncRelation(): void
    {
        $routingKey = 'sync.goods.option_plural';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\GoodsOptionPlural\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForCreateEntityTranslation(): void
    {
        $routingKey = 'create.goods.uk';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Translations\Goods\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForUpdateEntityTranslation(): void
    {
        $routingKey = 'change.goods.uk';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Translations\Goods\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForCreateRelationTranslation(): void
    {
        $routingKey = 'create.option.value_uk';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Translations\OptionValue\UpsertEventProcessor', $result);
    }

    public function testItWillReturnUpsertProcessorForChangeRelationTranslation(): void
    {
        $routingKey = 'change.option.value_uk';
        $result = $this->resolver->resolve($routingKey);
        $this->assertEquals('GoodsService\Translations\OptionValue\UpsertEventProcessor', $result);
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
