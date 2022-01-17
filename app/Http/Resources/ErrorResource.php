<?php

namespace App\Http\Resources;

use App\Exceptions\Api\ApiValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ErrorResource extends BaseResource
{
    public const FIELD_CODE = 'code';
    public const FIELD_ERROR = 'error';
    public const FIELD_MESSAGES = 'messages';
    public const FIELD_TRACE = 'trace';

    /**
     * @inerhitDoc
     */
    public static $wrap;

    /**
     * Field list
     *
     * @var array[]
     */
    private array $fields = [
        [
            'field' => self::FIELD_CODE,
            'fill_if_empty' => false,
        ],
        [
            'field' => self::FIELD_ERROR,
            'fill_if_empty' => false,
        ],
        [
            'field' => self::FIELD_MESSAGES,
            'fill_if_empty' => false,
        ],
        [
            'field' => self::FIELD_TRACE,
            'fill_if_empty' => false,
        ],
    ];

    /**
     * Handled exception
     *
     * @var HttpExceptionInterface
     */
    private HttpExceptionInterface $e;

    /**
     * @param HttpExceptionInterface $e
     */
    public function __construct(HttpExceptionInterface $e)
    {
        $this->e = $e;

        $data = [
            self::FIELD_CODE => $e->getStatusCode(),
            self::FIELD_ERROR => $this->resolveError($e),
            self::FIELD_MESSAGES => $this->resolveMessages($e),
        ];

        if (config('app.debug')) {
            $data[self::FIELD_TRACE] = $e->getTrace();
        }

        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function getResourceFields(): array
    {
        return $this->fields;
    }

    /**
     * Get error depending on status code
     *
     * @param HttpExceptionInterface $e
     * @return string
     */
    private function resolveError(HttpExceptionInterface $e): string
    {
        $code = $e->getStatusCode();

        switch ($code) {
            case Response::HTTP_BAD_REQUEST:
                $message = 'Bad Request';
                break;
            case Response::HTTP_NOT_FOUND:
                $message = 'Not Found';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $message = 'Method Not Allowed';
                break;
            case Response::HTTP_I_AM_A_TEAPOT:
                $message = "I'm a teapot";
                break;
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $message = 'Internal Server Error';
                break;
            default:
                $message = 'Unknown Error';
                break;
        }

        return $message;
    }

    /**
     * Get error messages depending on exception
     *
     * @param HttpExceptionInterface $e
     * @return array
     */
    private function resolveMessages(HttpExceptionInterface $e): array
    {
        if ($e instanceof ApiValidationException) {
            return $e->errors();
        }

        if ($e instanceof BadRequestHttpException) {
            return [
                $e->getMessage(),
            ];
        }

        if (config('app.debug')) {
            return [
                $e->getMessage(),
            ];
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)->setStatusCode($this->e->getStatusCode());
    }
}
