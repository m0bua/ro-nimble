<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleApiException($request, Throwable $e): JsonResponse
    {
        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            $e = $e->getResponse();
        }

        if ($e instanceof AuthenticationException) {
            $e = $this->unauthenticated($request, $e);
        }

        if ($e instanceof ValidationException) {
            $e = $this->convertValidationExceptionToResponse($e, $request);
        }

        return $this->prepareApiResponse($e);
    }

    /**
     * @param Throwable $e
     * @return JsonResponse
     * @noinspection PhpUndefinedFieldInspection
     */
    private function prepareApiResponse(Throwable $e): JsonResponse
    {
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
            case 400:
                $response['message'] = $e->original['message'];
                $response['errors'] = $e->original['errors'];
                break;
            default:
                $response['message'] = ($statusCode == 500 && !app()->isLocal()) ? 'Whoops, looks like something went wrong' : $e->getMessage();
                break;
        }

        $response['status'] = $statusCode;

        if (config('app.debug')) {
            $response['code'] = $e->getCode();
            $response['trace'] = $e->getTrace();
        }

        return response()->json($response, $statusCode);
    }
}
