<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Sort;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Sort::fromRequest($request);
        $this->assertEquals(get_class($result), Sort::class);
    }
}
