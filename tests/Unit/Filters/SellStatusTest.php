<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\SellStatuses;
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
        $result = SellStatuses::fromRequest($request);
        $this->assertEquals(get_class($result), SellStatuses::class);
    }
}
