<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function testApiV1RootTeapot()
    {
        $response = $this->get('/api/v1');

        $response->assertStatus(Response::HTTP_I_AM_A_TEAPOT);
    }
}
