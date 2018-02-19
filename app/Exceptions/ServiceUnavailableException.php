<?php

namespace App\Exceptions;

class ServiceUnavailableException extends ApiException
{
    protected $message = 'Service Unavailable';
    protected $code = 503;
}
