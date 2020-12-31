<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class UpdateAdminUser extends Migration
{
    const UPDATE_USERS = [
        [
            'firstname' => 'Enrico',
            'lastname' => 'Werkema',
            'email' => 'info@codecentral.nl',
            'password' => 'reex5BRAN6vust@dral',
        ],
        [
            'firstname' => 'Martin',
            'lastname' => 'Kok',
            'email' => 'info@onlinebouwers.nl',
            'password' => '9671CA@!',
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(self::UPDATE_USERS as $userData) {
            $user = User::where('email', $userData['email'])->first();
            if ($user !== null) {
                $user->update(['password' => $userData['password']]);
            } else {
                User::create($userData)->assign('admin');
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
