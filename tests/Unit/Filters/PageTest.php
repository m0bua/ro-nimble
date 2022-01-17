<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Page;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Page::fromRequest($request);
        $this->assertEquals(get_class($result), Page::class);
    }
}
