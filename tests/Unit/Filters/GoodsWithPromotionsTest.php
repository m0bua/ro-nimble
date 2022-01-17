<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\GoodsWithPromotions;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class GoodsWithPromotionsTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = GoodsWithPromotions::fromRequest($request);
        $this->assertEquals(get_class($result), GoodsWithPromotions::class);
    }
}
