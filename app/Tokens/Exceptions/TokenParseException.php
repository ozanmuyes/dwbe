<?php

namespace App\Tokens\Exceptions;

class TokenParseException extends TokenException
{
    protected $details = 'Request token is malformed.';
}
