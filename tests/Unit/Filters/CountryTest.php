<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Country;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Country::fromRequest($request);
        $this->assertEquals(get_class($result), Country::class);
    }
}
