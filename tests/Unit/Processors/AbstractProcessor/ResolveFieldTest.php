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

    public function testNull()
    {
        $result = $this->processor()->_resolveField(null);
        $this->assertNull($result);
    }

    public function testBool()
    {
        $result = $this->processor()->_resolveField(false);
        $this->assertEquals('false', $result);
    }

    public function testBoolAsString()
    {
        $result = $this->processor()->_resolveField('true');
        $this->assertEquals('true', $result);
    }

    public function testObject()
    {
        $object = new stdClass();
        $object->key = 'value';

        $result = $this->processor()->_resolveField($object);
        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testInt()
    {
        $result = $this->processor()->_resolveField(100);
        $this->assertEquals(100, $result);
    }

    public function testFloat()
    {
        $result = $this->processor()->_resolveField(14.88);
        $this->assertEquals(14.88, $result);
    }

    public function testString()
    {
        $result = $this->processor()->_resolveField('I am a string');
        $this->assertEquals('I am a string', $result);
    }

    public function testArray()
    {
        $result = $this->processor()->_resolveField([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $result);
    }
}
