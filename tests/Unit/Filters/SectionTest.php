<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Section;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Section::fromRequest($request);
        $this->assertEquals(get_class($result), Section::class);
    }
}
