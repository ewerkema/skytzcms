<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormField;
use App\Http\Requests;
use Validator;

class FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return FormField::all();
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
            'type' => 'required',
            'placeholder' => '',
            'form_id' => 'required|exists:forms,id',
            'required' => 'required|boolean',
            'options' => ''
        ], [], [
            'name' => 'Naam',
            'type' => 'Type',
            'placeholder' => 'Tijdelijke aanduiding',
            'form_id' => 'Het formulier',
            'required' => 'Aangeven of het veld verplicht is',
            'options' => 'Veld opties'
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

        if (!isset($input['options']))
            $input['options'] = array();

        $formField = FormField::create($input);

        return response()->json($formField, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formField = FormField::findOrFail($id);

        return response()->json($formField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param FormField $formField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormField $formField)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (!isset($input['options']))
            $input['options'] = array();

        if (!$formField->update($input))
            return response()->json(['message' => 'Updaten van het formulier is niet gelukt.'], 500);

        return response()->json($formField);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FormField::findOrFail($id)->delete();

        return response()->json(['message' => 'Formulier veld succesvol verwijderd. ']);
    }
}
