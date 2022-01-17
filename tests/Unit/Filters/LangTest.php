<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Lang;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class LangTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Lang::fromRequest($request);
        $this->assertEquals(get_class($result), Lang::class);
    }
}
