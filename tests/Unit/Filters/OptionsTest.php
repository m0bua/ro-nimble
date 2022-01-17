<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Options;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Options::fromRequest($request);
        $this->assertEquals(get_class($result), Options::class);
    }
}
