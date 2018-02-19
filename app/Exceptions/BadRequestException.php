<?php

namespace App\Exceptions;

class BadRequestException extends ApiException
{
    protected $message = 'Bad Request';
    protected $code = 400;
}
