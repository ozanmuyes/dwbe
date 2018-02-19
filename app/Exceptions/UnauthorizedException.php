<?php

namespace App\Exceptions;

class UnauthorizedException extends ApiException
{
    protected $message = 'Unauthorized';
    protected $code = 401;
}
