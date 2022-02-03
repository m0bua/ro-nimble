<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\States;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = States::fromRequest($request);
        $this->assertEquals(get_class($result), States::class);
    }
}
