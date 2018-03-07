<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\AdminCreated;
use App\Events\UserRegistered;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'create',
            //
        ]]);

        //
    }

    /**
     * List all the users.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function index()
    {
        if (Gate::denies('users.index')) {
            throw new UnauthorizedException(58);
        }

        return response()->json(['data' => User::all()]);
    }

    /**
     * Check if given user was set and her/his role is 'admin'.
     *
     * @param \App\User|\App\TokenUser|null $user
     *
     * @return bool
     */
    private function isUserAdmin($user)
    {
        if ($user === null) {
            return false;
        }

        return ($user->role === 'admin');
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
        /**
         * @var \App\TokenUser $user
         */
        $user = @$request->user();

        if (!$this->isUserAdmin($user)) {
            // User self registering - use database default
            unset($newUserAttributes['role']);
        }

        // Try to create the user and respond with it
        //
        $newUser = new User($newUserAttributes);
        $newUser->saveOrFail();

        // Decide and fire related event
        //
        /**
         * @var \App\Events\Event $event
         */
        $event = null;

        if ($this->isUserAdmin($newUser)) {
            // TODO Create 'password set token' token and link here (e.g. /password/set/[TOKEN] on front-end application)

            $event = new AdminCreated($newUser, $user);
        } else {
            // TODO Create 'validation token' and link here (e.g. /validate/[TOKEN] on front-end application)

            $event = new UserRegistered($newUser);
        }

        event($event);

        // Return the response (immediately)
        //
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
        if (Gate::denies('users.view', $id)) {
            throw new UnauthorizedException(33);
        }

        $user = User::findOrFail($id);

        return response()->json(['data' => $user]);
    }
}
