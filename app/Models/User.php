<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRolesAndAbilities;

    const ROLES = [
        'admin' => 'Admin',
        'user' => 'Gebruiker',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'username'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Boot function for using with User Events
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            $user->assign('user');
        });
    }

    /**
     * Return the role of the user.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        foreach (User::ROLES as $role => $name) {
            if ($this->isA($role))
                return $name;
        }

        return "Onbekende rol";
    }

    /**
     * Return the role type of the user.
     *
     * @return string
     */
    public function getRoleTypeAttribute()
    {
        foreach (User::ROLES as $role => $name) {
            if ($this->isA($role))
                return $role;
        }

        return "user";
    }

    /**
     * Retract all possible roles from the user.
     */
    public function retractAllRoles()
    {
        foreach (User::ROLES as $role => $name) {
            $this->retract($role);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }

    /**
     * Automatically encrypt passwords on set.
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getName()
    {
        if (empty($this->attributes['firstname']) && empty($this->attributes['lastname']))
            return "Geen naam";

        return ucfirst($this->attributes['firstname'])." ".ucfirst($this->attributes['lastname']);
    }

}
