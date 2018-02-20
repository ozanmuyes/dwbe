<?php

namespace App\Exceptions;

class TokenParseException extends ApiException
{
    protected $message = 'Bad Request';
    protected $code = 400;
    protected $details = 'Request token is malformed.';
}
