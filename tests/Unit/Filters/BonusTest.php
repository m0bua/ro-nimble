<?php

namespace Tests\Unit\Filters;

use App\Filters\Components\Bonus;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class BonusTest extends TestCase
{
    /**
     * @test
     */
    public function testFromRequest()
    {
        $request = new FormRequest();
        $result = Bonus::fromRequest($request);
        $this->assertEquals(get_class($result), Bonus::class);
    }
}
