<?php

use App\Form;
use App\FormField;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->unsigned();
            $table->string('type');
            $table->string('name');
            $table->text('placeholder');
            $table->text('options');
            $table->boolean('required');
            $table->foreign('form_id')->references('id')->on('forms');
        });

        if (config('skytz.old_cms'))
            $this->importFormFields();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_fields');
    }

    /**
     * Import existing forms fields from old table.
     *
     * @return void
     */
    public function importFormFields()
    {
        $formFields = DB::table('skytz_formelements')->get();
        $formFields->each(function($formField) {
            try {
                $form = Form::findOrFail($formField->formid);
            } catch (ModelNotFoundException $e) {
                dd("Couldn't find form with ID ".$formField->formid.": ".$e->getMessage());
            }

            $formField = FormField::create([
                'type' => $formField->elementtype,
                'name' => $formField->elementname,
                'options' => $formField->elementoptions,
                'required' => $formField->required,
            ]);
            $formField->setForm($form);
        });
        Schema::drop('skytz_formelements');
    }
}
