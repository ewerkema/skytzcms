<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Authorize the requests based on its policy.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @param int $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|unique:users,email,'.$id.',id',
            'password' => 'min:6|confirmed',
        ], [], [
            'firstname' => 'Voornaam',
            'lastname' => 'Achternaam',
            'email' => 'Email',
            'password' => 'Wachtwoord',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $user = User::create($input)->assign($request->get('role'));

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $this->validator($input, $user->id)->validate();

        if (!$user->update($input))
            return response()->json(['message' => 'Updaten van je account is niet gelukt.'], 500);

        $role = $request->get('role');
        if ($role != null && $user->isNotA($role)) {
            $user->retractAllRoles();
            $user->assign($role);
        }

        return response()->json($user);
    }

    /**
     * Update the current loggedin user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    function updateCurrentUser(Request $request) {
        return $this->update($request, auth()->user());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Gebruiker is succesvol verwijderd']);
    }
}
