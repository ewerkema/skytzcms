<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDefaultPasswordMartin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::where(['email' => 'info@skytz.nl'])->first();

        if ($user != null) {
            $user->password = 'skytz9672';
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $user = User::where(['email' => 'info@skytz.nl'])->first();

        if ($user != null) {
            $user->password = 'password';
            $user->save();
        }
    }
}
