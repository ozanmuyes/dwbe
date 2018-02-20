<?php


namespace App\Http\Controllers\Api\v1;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Try to create access and refresh tokens for the user via
     * given credentials. If user not found or credentials
     * invalid response with error.
     *
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\BadRequestException
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function create(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (@$credentials['email'] === null) {
            throw new BadRequestException(68);
        }
        if (@$credentials['password'] === null) {
            throw new BadRequestException(5);
        }

        $user = \App\User::where('email', $credentials['email'])->firstOrFail();

        // TODO Check if user's 'password' matches with the given `$password`

        throw new UnauthorizedException();
    }

    // logout
    public function delete()
    {
        //
    }
}
