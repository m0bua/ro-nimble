<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\SellStatus;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SellStatusTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = SellStatus::fromRequest($request);
        $this->assertEquals(get_class($result), SellStatus::class);
    }
}
