<?php

declare(strict_types=1);

namespace App\Support\Trait\Request;

use Illuminate\Contracts\Validation\Validator;

use Symfony\Component\HttpFoundation\Response;

use App\Exceptions\ApiException;
use App\Support\Constants\ExceptionsConstants;

trait ExceptionTrait
{
    /**
     * Throw Exception
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return never
     */
    public function throwException(Validator $validator): never
    {
        $message = implode(ExceptionsConstants::SPLIT_MARK, $validator->errors()->all());
        throw new ApiException($message, Response::HTTP_BAD_REQUEST);
    }
}
