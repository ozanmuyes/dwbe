<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\BadRequestException;
use App\Exceptions\TokenParseException;
use App\Http\Controllers\Controller;
use App\Tokens\AccessToken;
use App\Tokens\RefreshToken;
use App\User;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'create',
            'refresh',
            //
        ]]);

        //
    }

    /**
     * Try to create access and refresh tokens for the user via
     * given credentials. If user not found or credentials
     * invalid response with error. Acts like 'login'.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BadRequestException
     */
    public function create(Request $request)
    {
        // Get credentials from request
        //
        $credentials = $request->only(['email', 'password']);

        if (@$credentials['email'] === null) {
            throw new BadRequestException(68);
        }
        if (@$credentials['password'] === null) {
            throw new BadRequestException(5);
        }

        // Find user from database
        $user = User::where('email', $credentials['email'])->firstOrFail();

        // Check if user's password matches with the given password
        //
        $hasher = new Hasher();
        if (!$hasher->check($credentials['password'], $user->getAuthPassword())) {
            throw new BadRequestException(23);
        }

        // Create tokens
        //
        /**
         * @var \App\Tokens\AccessToken $accessToken
         */
        $accessToken = null;
        try {
            // TODO Consider 'for' field for the token audience
            $accessToken = new AccessToken($user, 'app://dwfe');
        } catch (\Exception $e) {
            // TODO throw custom (API) exception
        }

        /**
         * @var \App\Tokens\RefreshToken $refreshToken
         */
        $refreshToken = null;
        try {
            $refreshToken = new RefreshToken($user);
        } catch (\Exception $e) {
            // TODO throw custom (API) exception
        }

        return response()->json([
            'data' => [
                'access_token' => (string) $accessToken,
                'refresh_token' => (string) $refreshToken,
            ],
        ], 201);
    }

    public function delete()
    {
        // NOTE Since tokens were stored on the client-side nothing to do here
        // TODO Maybe remove tokens' ID (`jti`) from the whitelist (if any)
    }

    /**
     * Create a new access token using refresh token.
     * Expired (access) token MUST be sent via the
     * authorization header.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\TokenParseException
     * @throws \App\Exceptions\BadRequestException
     */
    public function refresh(Request $request)
    {
        // Get tokens from request
        $tokens = $request->only(['access_token', 'refresh_token']);
        if (count($tokens) !== 2) { // No token on (authorization) header
            throw new BadRequestException(17);
        }

        /**
         * @var \Lcobucci\JWT\Token $refreshToken
         */
        $refreshToken = null;
        try {
            $refreshToken = (new Parser())->parse($tokens['refresh_token']);
        } catch (\InvalidArgumentException $e) {
            throw new TokenParseException(45);
        } catch (\Exception $e) {
            throw new TokenParseException(67);
        }

        // TODO Create a new access token

        return response()->json([
            'data' => [
                'access_token' => 'n3w4Cc3sS70k3n',
            ],
        ]);
    }
}
