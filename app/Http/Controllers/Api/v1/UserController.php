<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * List all the users.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function index()
    {
        if (Gate::denies('index-users')) {
            throw new UnauthorizedException(58);
        }

        return response()->json(['data' => \App\User::all()]);
    }

    /**
     * Create a new user. Visitors MAY self register themselves
     * or an existing user may create a new user. If visitor
     * self registering it's role will be the database
     * default, otherwise if existing user's role
     * able to set the new user's role, it will
     * be used instead of the database default.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        // Since everyone can create user, do NOT check for Gate

        $newUserAttributes = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'role' => 'nullable',
        ]);

        // Check if the visitor self registering.
        //
        $user = @$request->user();

        // FIXME When role input was unset, the `$newUser` below won't have the 'role' attribute
        if ($user === null) {
            // User self registering - use database default
            unset($newUserAttributes['role']);
        } else {
            // An existing user creating a new user
            if ($user->role === 'admin') {
                // Can decide new user's role
            } else {
                // Can NOT decide new user's role - use database default
                unset($newUserAttributes['role']);
            }
        }

        // Try to create the user and respond with it
        //
        $newUser = new \App\User($newUserAttributes);
        $newUser->saveOrFail();

        return response()->json(['data' => $newUser], 201);
    }

    /**
     * View user via its database ID.
     *
     * @param int $id User ID to view
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function view(int $id)
    {
        if (Gate::denies('view-user', $id)) {
            throw new UnauthorizedException(33);
        }

        $user = \App\User::findOrFail($id);

        return response()->json(['data' => $user]);
    }
}
