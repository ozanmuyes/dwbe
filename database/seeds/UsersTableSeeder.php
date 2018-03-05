<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Ozan',
            'last_name' => 'Müyes',
            'username' => 'ozan.muyes',
            'email' => 'ozan@muyes.co',
            'password' => 'ozan@muyes.co',
            'role' => 'admin',
        ]);

        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe',
            'email' => 'john@does.co',
            'password' => 'john@does.co',
        ]);
    }
}
