<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Categories;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Categories::fromRequest($request);
        $this->assertEquals(get_class($result), Categories::class);
    }
}
