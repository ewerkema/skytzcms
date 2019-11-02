<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAdminAccountEmail extends Migration
{
    public $oldEmail = 'skytz.nl';
    public $newEmail = 'onlinebouwers.nl';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $old = User::where('email', 'like', "%$this->oldEmail")->get();

        foreach ($old as $user) {
            $user->update(['email' => str_replace($this->oldEmail, $this->newEmail, $user->email)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $new = User::where('email', 'like', "%$this->newEmail")->get();

        foreach ($new as $user) {
            $user->update(['email' => str_replace($this->newEmail, $this->oldEmail, $user->email)]);
        }
    }
}
