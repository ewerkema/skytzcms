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
            'password' => 'codecentral',
        ];
        $this->generateAdmin($admin);

        $martin = [
            'firstname' => 'Martin',
            'lastname' => 'Kok',
            'email' => 'info@skytz.nl',
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
