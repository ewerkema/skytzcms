<?php

use App\Facades\ImportTable;
use App\Models\User;
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
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        ImportTable::import('skytz_admins', function($user) {
            User::create([
                'firstname' => '',
                'lastname' => '',
                'email' => $user->email,
                'username' => $user->username,
                'password' => 'skytz9672',
            ]);
        });

        $this->generateDefaultUsers();
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
     * Generate default users from every copy of the system.
     */
    private function generateDefaultUsers()
    {
        $admin = [
            'firstname' => 'Enrico',
            'lastname' => 'Werkema',
            'email' => 'info@codecentral.nl',
            'username' => 'ewerkema',
            'password' => 'codecentral',
        ];
        $this->generateAdmin($admin);

        $martin = [
            'firstname' => 'Martin',
            'lastname' => 'Kok',
            'email' => 'info@skytz.nl',
            'username' => 'skytz',
            'password' => 'skytz9672',
        ];
        $this->generateAdmin($martin);
    }

    /**
     * Function to generate user and check if it already exists.
     *
     * @param $user
     */
    private function generateAdmin($user)
    {
        if (!User::where('email', $user['email'])->count())
            User::create($user)->assign('admin');
    }


}
