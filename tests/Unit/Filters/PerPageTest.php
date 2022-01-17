<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\PerPage;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class PerPageTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = PerPage::fromRequest($request);
        $this->assertEquals(get_class($result), PerPage::class);
    }
}
