<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddUserRoles extends Migration
{
    private $admins = ["info@codecentral.nl", "info@skytz.nl"];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->assignRoles();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->retractRoles();
    }

    /**
     * Assign admin rights to all admin with the given emails.
     *
     * @return void
     */
    private function assignRoles()
    {
        $users = User::all();

        foreach ($users as $user) {
            if (in_array($user->email, $this->admins)) {
                $user->assign('admin');
            } else {
                $user->assign('user');
            }
        }
    }

    /**
     * Retract all admin rights from admins with the given emails.
     *
     * @return void
     */
    private function retractRoles()
    {
        $users = User::all();

        foreach ($users as $user) {
            if (in_array($user->email, $this->admins)) {
                $user->retract('admin');
            } else {
                $user->retract('user');
            }
        }
    }
}
