<?php

namespace Tests\Unit\Http\Resources;

use App\Exceptions\Api\ApiValidationException;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ErrorResourceTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => false]);
    }

    public function testItWilSetStatusCodeToResponse(): void
    {
        $resource = new ErrorResource(new BadRequestHttpException());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $resource->response()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveMessagesForValidationError(): void
    {
        try {
            Validator::validate([], [
                'test_field' => 'required',
                'test_field2' => 'required',
            ]);
        } catch (ValidationException $e) {
            $exception = new ApiValidationException($e->validator);

            $this->assertEquals([
                'The test_field field is required.',
                'The test_field2 field is required.',
            ], $this->invokeResolveMessages($exception));
        }
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveMessagesForCustomValidationError(): void
    {
        $this->assertEquals([
            'Validation message.',
        ], $this->invokeResolveMessages(new BadRequestHttpException('Validation message.')));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveMessages(): void
    {
        $this->assertEmpty($this->invokeResolveMessages(new HttpException(0)));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForBadRequest(): void
    {
        $this->assertEquals(
            'Bad Request',
            $this->invokeResolveError(new BadRequestHttpException())
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForNotFound(): void
    {
        $this->assertEquals(
            'Not Found',
            $this->invokeResolveError(new NotFoundHttpException())
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForMethodNotAllowed(): void
    {
        $this->assertEquals(
            'Method Not Allowed',
            $this->invokeResolveError(new MethodNotAllowedHttpException(['get']))
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForIAmATeapot(): void
    {
        $this->assertEquals(
            "I'm a teapot",
            $this->invokeResolveError(new HttpException(Response::HTTP_I_AM_A_TEAPOT))
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForInternalServerError(): void
    {
        $this->assertEquals(
            'Internal Server Error',
            $this->invokeResolveError(new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR))
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanResolveErrorForOtherError(): void
    {
        $this->assertEquals(
            'Unknown Error',
            $this->invokeResolveError(new HttpException(0))
        );
    }

    /**
     * Invoke private static method 'resolveError'
     *
     * @param HttpExceptionInterface $e
     * @return string
     * @throws ReflectionException
     */
    private function invokeResolveError(HttpExceptionInterface $e): string
    {
        $method = new ReflectionMethod(ErrorResource::class, 'resolveError');
        $method->setAccessible(true);

        return $method->invoke(new ErrorResource(new HttpException(0)), $e);
    }

    /**
     * Invoke private static method 'resolveMessages'
     *
     * @param HttpExceptionInterface $e
     * @return array
     * @throws ReflectionException
     */
    private function invokeResolveMessages(HttpExceptionInterface $e): array
    {
        $method = new ReflectionMethod(ErrorResource::class, 'resolveMessages');
        $method->setAccessible(true);

        return $method->invoke(new ErrorResource(new HttpException(0)), $e);
    }
}
