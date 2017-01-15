<?php

use App\Models\Form;
use App\Models\FormField;
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
            $table->text('placeholder')->nullable();
            $table->text('options')->nullable();
            $table->boolean('required');
            $table->foreign('form_id')->references('id')->on('forms');
        });

        ImportTable::import('skytz_formelements', function ($formField) {
            try {
                $form = Form::findOrFail($formField->formid);
            } catch (ModelNotFoundException $e) {
                echo "Couldn't find form with ID ".$formField->formid.": ".$e->getMessage()."\n";
                return;
            }

            if ($formField->elementtype == "input")
                $formField->elementtype = "text";

            FormField::create([
                'type' => $formField->elementtype,
                'name' => $formField->elementname,
                'options' => $formField->elementoptions,
                'required' => $formField->required,
                'form_id' => $form->id,
            ]);
        });
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

}
