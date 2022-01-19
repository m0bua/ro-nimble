<?php

namespace Tests\Unit\Processors\Processor;

use PHPUnit\Framework\TestCase;

class PrepareDataTest extends TestCase
{
    protected array $data = [
        'field1' => 'value1',
        'field2' => 'value2',
        'field3' => 'value3',
        'field4' => 'value4',
    ];

    protected array $aliases = [
        'field4_in_array' => 'field4',
    ];

    protected function processor(): TestProcessor
    {
        return new TestProcessor(new TestModel());
    }

    public function testItWillReturnDataWithoutAliases(): void
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
            'field4' => 'value4',
        ];
        $result = $this->processor()->_prepareData($data);

        $this->assertEquals($data, $result);
    }

    public function testItWillReturnDataWithAliases(): void
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
            'field4_in_array' => 'value4',
        ];
        $aliases = [
            'field4_in_array' => 'field4',
        ];
        $result = $this->processor()->_prepareData($data, $aliases);

        $expected = [
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
            'field4' => 'value4',
        ];
        $this->assertEquals($expected, $result);
    }
}
