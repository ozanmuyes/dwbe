<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\AdminCreated;
use App\Events\UserRegistered;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Tokens\PasswordSetToken;
use App\Tokens\VerificationToken;
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
        if (Gate::denies('user.index')) {
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
            'password' => 'nullable',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'role' => 'nullable',
        ]);

        // Check if the visitor self registering.
        //
        /** @var \App\TokenUser $user */
        $user = @$request->user();

        if (!$this->isUserAdmin($user)) {
            // User self registering - use database default
            unset($newUserAttributes['role']);
        }

        // FIXME TR bir admin, rolü belirlemeden kullanıcı create etmek isterse self registering gibi algılıyor; \
        //          gelen password'ü kaydediyor, rolu ve verification bekliyor. Postman'deki _FIXME_'ye bak.
        //          Bunu (bir admin, rol belirtmeden kullanıcı create ederken) önlemek için 'must_change_password'
        //          gibi bir kolon eklenebilir migration ile.
        if (@$newUserAttributes['role'] === 'admin') {
            // Clear the given password, so that the new admin set his/her password
            unset($newUserAttributes['password']);

            // Create and set the password set token
            //
            $passwordSetToken = new PasswordSetToken($newUserAttributes['email']);
            $newUserAttributes['password_set_token'] = (string) $passwordSetToken;
        } else {
            // Create and set the verification token
            //
            $verificationToken = new VerificationToken($newUserAttributes['email']);
            $newUserAttributes['verification_token'] = (string) $verificationToken;
        }

        // Try to create the user
        //
        $newUser = new User($newUserAttributes);
        $newUser->saveOrFail();

        // Decide and fire related event
        //
        $event = ($this->isUserAdmin($newUser))
            ? new AdminCreated($newUser, $user)
            : new UserRegistered($newUser);
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
        if (Gate::denies('user.view', $id)) {
            throw new UnauthorizedException(33);
        }

        $user = User::findOrFail($id);

        return response()->json(['data' => $user]);
    }

    public function setPassword()
    {
        // TODO Write and test set password action that requires 'password set' token and a password (duh)
    }
}
