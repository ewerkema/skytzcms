<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        $this->importUsers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

    /**
     * Import existing users from old table.
     *
     * @return void
     */
    public function importUsers()
    {
        $users = DB::table('skytz_admins')->get();
        $users->each(function($user) {
            User::create([
                'firstname' => '',
                'lastname' => '',
                'email' => $user->email,
                'username' => $user->username,
                'password' => Hash::make('password')
            ]);
        });
        $this->dropOldUsersTable();
    }

    /**
     * Drop old users table.
     *
     * @return void
     */
    public function dropOldUsersTable()
    {
        Schema::drop('skytz_admins');
    }
}
