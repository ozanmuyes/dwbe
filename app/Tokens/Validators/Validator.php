<?php

namespace App\Tokens\Validators;

use App\Tokens\Exceptions\{
    TokenParseException,
    TokenUnknownTypeException,
    TokenValidationException
};
use Illuminate\Support\Str;
use Lcobucci\JWT\Parser;

abstract class Validator
{
    /**
     * Regardless of the token type, this function tries to validate
     * given token (as string) by parsing it first and then
     * invoking appropriate validator class' validate
     * method.
     * So this function depends on the token type to figure out the
     * validator class that corresponds to that very token.
     * The class name of the validator will be the
     * camel-case version of the token type.
     *
     * @param string $tokenString
     * @return \Lcobucci\JWT\Token Returns the token if it is valid
     * @throws \App\Tokens\Exceptions\TokenException
     */
    static public function validate($tokenString): \Lcobucci\JWT\Token
    {
        /**
         * @var \Lcobucci\JWT\Token $token
         */
        $token = null;
        try {
            $token = (new Parser())->parse($tokenString);
        } catch (\InvalidArgumentException $e) {
            throw new TokenParseException(45);
        } catch (\Exception $e) {
            throw new TokenParseException(67);
        }

        // Get (validator) class name from the token type
        //
        $tokenType = @$token->getClaim('ttp');
        if ($tokenType === null) {
            throw new TokenUnknownTypeException(9);
        }

        $className = ucfirst(Str::camel($tokenType)) . 'TokenValidator';
        $classNameWithNS = (__NAMESPACE__ . '\\' . $className);
        if (!class_exists($classNameWithNS)) {
            throw new TokenUnknownTypeException(52);
        }

        // Make the specific validator class to try validate the token
        //
        /** @var \App\Tokens\Validators\ValidatesTokens $clazz */
        $clazz = new $classNameWithNS;
        if ($clazz->validateToken($token) !== true) {
            // This is an undesired occasion; the specific validator SHOULD
            // throw an appropriate exception (since the token is invalid)
            throw new TokenValidationException(39);
        }

        return $token;
    }
}
