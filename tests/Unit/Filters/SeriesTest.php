<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Series;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SeriesTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Series::fromRequest($request);
        $this->assertEquals(get_class($result), Series::class);
    }
}
