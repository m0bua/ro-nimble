<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Mockery\MockInterface;
use Tests\TestCase;
use Throwable;

class HandlerTest extends TestCase
{
    private Handler $handler;

    /**
     * @inerhitDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => false]);
        config(['app.env' => 'production']);
        $this->handler = App::make(Handler::class);
    }

    /**
     * @throws Throwable
     */
    public function testItCanRenderInternalErrors(): void
    {
        $e = new Exception();
        $request = $this->mockRequest();
        $expected = new JsonResponse([
            ErrorResource::FIELD_CODE => Response::HTTP_INTERNAL_SERVER_ERROR,
            ErrorResource::FIELD_ERROR => 'Internal Server Error',
            ErrorResource::FIELD_MESSAGES => [],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertEquals($expected, $this->handler->render($request, $e));
    }

    /**
     * @throws Throwable
     */
    public function testItCanRenderValidationErrors(): void
    {
        $data = [];
        $rules = [
            'category_id' => [Rule::requiredIf(!Arr::has($data, 'promotion_id'))],
            'promotion_id' => [Rule::requiredIf(!Arr::has($data, 'category_id'))],
        ];
        $request = $this->mockRequest();
        $expected = new JsonResponse([
            ErrorResource::FIELD_CODE => Response::HTTP_BAD_REQUEST,
            ErrorResource::FIELD_ERROR => 'Bad Request',
            ErrorResource::FIELD_MESSAGES => [
                'The category_id field is required.',
                'The promotion_id field is required.',
            ],
        ], Response::HTTP_BAD_REQUEST);

        try {
            Validator::validate($data, $rules);
        } catch (ValidationException $e) {
            $this->assertEquals($expected, $this->handler->render($request, $e));
        }
    }

    /**
     * @throws Throwable
     */
    public function testItCanRenderHttpErrors(): void
    {
        $e = new ModelNotFoundException();
        $request = $this->mockRequest();
        $expected = new JsonResponse([
            ErrorResource::FIELD_CODE => Response::HTTP_NOT_FOUND,
            ErrorResource::FIELD_ERROR => 'Not Found',
            ErrorResource::FIELD_MESSAGES => [],
        ], Response::HTTP_NOT_FOUND);

        $this->assertEquals($expected, $this->handler->render($request, $e));
    }

    /**
     * Mock request for handler
     *
     * @return MockInterface|Request
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    private function mockRequest()
    {
        return $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('is')->once()->with('api/*')->andReturn(true);
        });
    }
}
