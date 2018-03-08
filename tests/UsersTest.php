<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class UsersTest extends TestCase
{
//    use DatabaseMigrations;

    /**
     * @test
     *
     * Test: GET /api/v1/users
     */
    public function list_users_with_necessary_fields()
    {
        /**
         * @var \App\TokenUser $tokenUser
         */
        $tokenUser = factory(App\TokenUser::class, 'admin')->make();


        /**
         * @var \Illuminate\Database\Eloquent\Collection $manufacturedUsers
         */
        $manufacturedUsers = factory(App\User::class, 2)->create();


        $this
            ->actingAs($tokenUser)
            ->json('GET', '/api/v1/users')
            ->seeStatusCode(200)
            ->seeJson($manufacturedUsers[0]->toArray())
            ->seeJson($manufacturedUsers[1]->toArray())
        ;
    }

    /**
     * @test
     *
     * Test: GET /api/v1/users/1
     */
    public function get_user_with_necessary_fields()
    {
        /**
         * @var \App\TokenUser $tokenUser
         */
        $tokenUser = factory(App\TokenUser::class, 'admin')->make();


        /**
         * @var \App\User $manufacturedUser
         */
        $manufacturedUser = factory(App\User::class)->create();


        $this
            ->actingAs($tokenUser)
            ->json('GET', "/api/v1/users/{$manufacturedUser->id}")
            ->seeStatusCode(200)
            ->seeJson($manufacturedUser->toArray())
        ;
    }

    /**
     * @test
     *
     * Test: POST /api/v1/users
     *
     * Visitor trying to login with all the necessary fields filled correctly.
     */
    public function visitor_register_self()
    {
        $createdUserData = (factory(App\User::class)->make())->toArray();
        $createdUserDataWithPassword = array_merge($createdUserData, ['password' => $createdUserData['email']]);
        $persistedUser = array_merge($createdUserData, ['role' => 'user']);


        $this
            ->expectsEvents([App\Events\UserRegistered::class])
            ->json('POST', '/api/v1/users', $createdUserDataWithPassword, ['Content-Type' => 'application/json'])
            ->seeStatusCode(201)
            ->seeJson($createdUserData)
            ->seeInDatabase('users', $persistedUser)
            // Do NOT see plain password in the database
            ->notSeeInDatabase('users', ['password' => $createdUserDataWithPassword['password']])
        ;
    }

    /**
     * @test
     *
     * Test: POST /api/v1/users
     *
     * An admin creates a new user with all the necessary fields filled correctly,
     * also CAN set a role for the new user.
     */
    public function admin_create_new_user()
    {
        /**
         * @var \App\TokenUser $tokenUser
         */
        $tokenUser = factory(App\TokenUser::class, 'admin')->make();


        $createdUserData = (factory(App\User::class)->make())->toArray();
        $createdUserDataWithPasswordAndRole = array_merge(
            $createdUserData,
            [
                'password' => $createdUserData['email'], // The password MUST be omitted
                'role' => 'admin'
            ]
        );
        $persistedUser = array_merge($createdUserData, ['role' => 'admin']);


        $this
            ->actingAs($tokenUser)
            ->expectsEvents([App\Events\AdminCreated::class])
            ->json('POST', '/api/v1/users', $createdUserDataWithPasswordAndRole)
            ->seeStatusCode(201)
            ->seeJson($createdUserData)
            ->seeInDatabase('users', $persistedUser)
            // Do NOT see plain password in the database
            ->notSeeInDatabase('users', ['password' => $createdUserDataWithPasswordAndRole['password']])
        ;
    }
}
