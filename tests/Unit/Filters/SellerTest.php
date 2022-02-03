<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Sellers;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SellerTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Sellers::fromRequest($request);
        $this->assertEquals(get_class($result), Sellers::class);
    }
}
