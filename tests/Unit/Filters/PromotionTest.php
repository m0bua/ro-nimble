<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Promotion;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class PromotionTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Promotion::fromRequest($request);
        $this->assertEquals(get_class($result), Promotion::class);
    }
}
