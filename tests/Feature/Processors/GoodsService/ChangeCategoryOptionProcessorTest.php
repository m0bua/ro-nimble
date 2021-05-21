<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryOption;
use App\Processors\GoodsService\ChangeCategoryOptionProcessor;
use Mockery\MockInterface;
use Tests\TestCase;

class ChangeCategoryOptionProcessorTest extends TestCase
{
    /**
     * @test
     * @noinspection PhpParamsInspection
     */
    public function testSuccessUpdating()
    {
        $model = $this->mock(CategoryOption::class, function (MockInterface $mock) {
            $mock->shouldReceive('getFillable')->once()->andReturn([
                'id',
                'category_id',
                'option_id',
                'value',
            ]);
            $mock->shouldReceive('write')->once()->andReturn($mock);
            $mock->shouldReceive('whereId')->once()->andReturn($mock);
            $mock->shouldReceive('update')->once();
        });
        $message = $this->mock(MessageInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getField')->with('data.id')->once()->andReturn(1);
            $mock->shouldReceive('getField')->with('data')->once()->andReturn([
                'id' => 1,
                'category_id' => 1,
                'option_id' => 1,
                'value' => 1,
            ]);
        });

        $processor = new ChangeCategoryOptionProcessor($model);
        $result = $processor->processMessage($message);

        $this->assertEquals(Codes::SUCCESS, $result);
    }
}
