<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Producers;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class ProducersTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Producers::fromRequest($request);
        $this->assertEquals(get_class($result), Producers::class);
    }
}
