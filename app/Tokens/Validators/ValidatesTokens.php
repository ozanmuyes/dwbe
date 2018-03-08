<?php

namespace App\Tokens\Validators;

// TODO Write validators for other tokens (other than access token) (i.e. refresh, password set, validation etc.)

interface ValidatesTokens
{
    /**
     * Validates and verifies parsed token.
     * If token is invalid for some reason
     * throw an appropriate exception.
     * Return/Throw nothing otherwise.
     *
     * @param \Lcobucci\JWT\Token $token
     * @return bool Returns true if the token is valid, otherwise
     *         an appropriate exception MUST be thrown
     */
    public function validateToken($token): bool;
}
