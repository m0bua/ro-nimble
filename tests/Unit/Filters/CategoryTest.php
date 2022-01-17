<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Category;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Category::fromRequest($request);
        $this->assertEquals(get_class($result), Category::class);
    }
}
