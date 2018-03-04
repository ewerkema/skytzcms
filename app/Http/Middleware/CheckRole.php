<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

class CheckRole
{
    /**
     * The authentication factory instance.
     *
     * @var Auth
     */
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $roles
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $this->auth->authenticate();

        foreach ($roles as $role) {
            if ($request->user()->isA($role)) {
                return $next($request);
            }
        }

        Session::flash('flash_message', 'Je hebt niet de juiste rechten om deze pagina te bekijken. Is dit niet de bedoeling? Neem dan contact op met info@skytz.nl.');
        Session::flash('flash_title', 'Niet toegestaan');

        return redirect()->to('/cms/index');
    }
}
