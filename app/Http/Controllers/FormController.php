<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Input;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Form::all();
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'email',
            'message' => '',
        ], [], [
            'name' => 'Naam',
            'email' => 'Email',
            'message' => 'Succes melding',
        ]);
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

        if (!isset($input['email']))
            $input['email'] = Auth::user()->email;

        $form = Form::create($input);

        return response()->json($form, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = Form::findOrFail($id);

        return response()->json($form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Form $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (!$form->update($input))
            return response()->json(['message' => 'Updaten van het formulier is niet gelukt.'], 500);

        return response()->json($form);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->fields()->delete();
        $form->delete();

        return response()->json(['message' => 'Formulier is succesvol verwijderd']);
    }
}
