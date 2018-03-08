<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

        DB::table('users')->insert([
            'first_name' => 'Ozan',
            'last_name' => 'Müyes',
            'username' => 'ozan.muyes',
            'email' => 'ozan@muyes.co',
            'password' => Hash::make('ozan@muyes.co'), // The password same as the email
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
            'verified_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe',
            'email' => 'john@does.co',
            'password' => Hash::make('john@does.co'), // The password same as the email
            'created_at' => $now,
            'updated_at' => $now,
            'verified_at' => $now,
        ]);
    }
}
