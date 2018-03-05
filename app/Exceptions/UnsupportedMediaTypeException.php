<?php

namespace App\Exceptions;

class UnsupportedMediaTypeException extends ApiException
{
    protected $message = 'Unsupported Media Type';
    protected $code = 415;
}
