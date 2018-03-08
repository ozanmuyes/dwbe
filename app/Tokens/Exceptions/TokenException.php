<?php

namespace App\Tokens\Exceptions;

use App\Exceptions\ApiException;

abstract class TokenException extends ApiException
{
    protected $message = 'Invalid Token';
    protected $code = 400;
}
