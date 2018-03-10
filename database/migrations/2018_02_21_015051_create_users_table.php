<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')
                ->nullable(false);
            $table->string('last_name')
                ->nullable(false);
            $table->string('username')
                ->unique()
                ->nullable(false);
            $table->string('email')
                ->unique()
                ->nullable(false);
            $table->string('password_set_token', 511)
                ->nullable();
            $table->string('password')
                ->nullable();
            $table->string('role')
                ->default('user'); // 'admin', 'mod' or 'user'
            $table->timestamps();
            $table->string('verification_token', 511)
                ->nullable();
            $table->timestamp('verified_at')
                ->nullable();
            $table->boolean('is_enabled')
                ->default(true);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
