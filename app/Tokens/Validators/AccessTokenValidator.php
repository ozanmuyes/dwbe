<?php

namespace App\Tokens\Validators;

class AccessTokenValidator implements ValidatesTokens
{
    /**
     * Validates and verifies parsed token.
     * If token is invalid for some reason
     * throw an appropriate exception.
     * Return/Throw nothing otherwise.
     *
     * @param \Lcobucci\JWT\Token $token
     * @return bool
     */
    public function validateToken($token): bool
    {
        // TODO Implement validateToken() method.

        // TODO Check for required claims existence (for the application)

        return true;
    }
}
