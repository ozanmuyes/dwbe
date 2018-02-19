<?php

namespace App\Exceptions;

class NotAcceptableException extends ApiException
{
    protected $message = 'Not Acceptable';
    protected $code = 406;
    protected $details = 'Accepts header is missing.';
}
