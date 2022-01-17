<?php

namespace App\Exceptions\Api;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

final class ApiValidationException extends BadRequestHttpException
{
    /**
     * Validator instance
     *
     * @var Validator
     */
    private Validator $validator;

    /**
     * @param Validator $validator
     * @param Throwable|null $previous
     */
    public function __construct(Validator $validator, Throwable $previous = null)
    {
        $this->validator = $validator;

        parent::__construct(
            'The given data was invalid',
            $previous
        );
    }

    /**
     * Get error messages array
     *
     * @return array
     */
    public function errors(): array
    {
        return collect($this->validator->errors()->messages())
            ->transform(function (array $messages, string $key) {
                $displayableKey = $this->validator->getDisplayableAttribute($key);
                return collect($messages)->map(fn(string $message) => Str::replace($displayableKey, $key, $message));
            })
            ->flatten()
            ->toArray();
    }
}
