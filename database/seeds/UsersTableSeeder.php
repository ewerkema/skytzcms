<?php

use App\Models\User;
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
        $admin = [
            'firstname' => 'Enrico',
            'lastname' => 'Werkema',
            'email' => 'info@codecentral.nl',
            'password' => 'reex5BRAN6vust@dral',
        ];
        $this->generateAdmin($admin);

        $martin = [
            'firstname' => 'Martin',
            'lastname' => 'Kok',
            'email' => 'info@onlinebouwers.nl',
            'password' => '9671CA@!',
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
