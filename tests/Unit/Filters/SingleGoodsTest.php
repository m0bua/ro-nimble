<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\SingleGoods;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SingleGoodsTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = SingleGoods::fromRequest($request);
        $this->assertEquals(get_class($result), SingleGoods::class);
    }
}
