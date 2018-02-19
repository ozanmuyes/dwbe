<?php

namespace App\Exceptions;

class ExampleException extends ApiException
{
    protected $message = 'Example Exception';
    protected $code = 418;
    protected $details = 'This is an example exception manually thrown on an action.';
}
