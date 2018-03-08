<?php

namespace App\Tokens\Exceptions;

class TokenUnknownTypeException extends TokenException
{
    protected $details = 'Token type wasn\'t set or unknown.';
}
