<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Price;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Price::fromRequest($request);
        $this->assertEquals(get_class($result), Price::class);
    }
}
