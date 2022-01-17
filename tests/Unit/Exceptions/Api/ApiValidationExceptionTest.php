<?php

namespace Tests\Unit\Exceptions\Api;

use App\Exceptions\Api\ApiValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ApiValidationExceptionTest extends TestCase
{
    public function testItWillReturnErrorMessagesWithSnakeCaseAttributes(): void
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
            ], $exception->errors());
        }
    }

}
