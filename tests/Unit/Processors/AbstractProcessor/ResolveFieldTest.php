<?php

namespace Tests\Unit\Processors\AbstractProcessor;

use PHPUnit\Framework\TestCase;
use stdClass;

class ResolveFieldTest extends TestCase
{
    protected function processor(): TestProcessor
    {
        return new TestProcessor();
    }

    public function testItWillReturnNullIfNullProvided()
    {
        $result = $this->processor()->_resolveField(null);
        $this->assertNull($result);
    }

    public function testItWillPrepareBoolProvided()
    {
        $result = $this->processor()->_resolveField(false);
        $this->assertEquals('false', $result);
    }

    public function testItWillPrepareBooleanStringProvided()
    {
        $result = $this->processor()->_resolveField('true');
        $this->assertEquals('true', $result);
    }

    public function testItWillReturnArrayIfObjectProvided()
    {
        $object = new stdClass();
        $object->key = 'value';

        $result = $this->processor()->_resolveField($object);
        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testItWillReturnIntIfIntProvided()
    {
        $result = $this->processor()->_resolveField(100);
        $this->assertEquals(100, $result);
    }

    public function testItWillReturnFloatIfFloatProvided()
    {
        $result = $this->processor()->_resolveField(14.88);
        $this->assertEquals(14.88, $result);
    }

    public function testItWillReturnStringIfStringProvided()
    {
        $result = $this->processor()->_resolveField('I am a string');
        $this->assertEquals('I am a string', $result);
    }

    public function testItWillReturnArrayIfArrayProvided()
    {
        $result = $this->processor()->_resolveField([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $result);
    }
}
