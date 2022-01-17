<?php

namespace App\Exceptions;

use App\Exceptions\Api\ApiException;
use App\Exceptions\Api\ApiValidationException;
use App\Http\Resources\ErrorResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->renderApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle and render API error
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     * @noinspection PhpUnusedParameterInspection
     */
    private function renderApiException(Request $request, Throwable $e): JsonResponse
    {
        $e = $this->prepareException($e);

        if ($e instanceof ValidationException) {
            $e = new ApiValidationException($e->validator);
        } elseif (!$e instanceof HttpExceptionInterface) {
            $e = new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

        return (new ErrorResource($e))->response();
    }
}
