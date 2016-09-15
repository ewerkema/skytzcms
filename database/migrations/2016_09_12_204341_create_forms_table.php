<?php

use App\Models\Form;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('redirect');
            $table->text('message');
            $table->timestamps();
        });

        ImportTable::import('skytz_forms', function ($form) {
            Form::create([
                'name' => $form->formname,
                'email' => $form->formemail,
                'redirect' => $form->redirect,
                'message' => $form->thankyou_message,
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
        Schema::drop('forms');
    }
}
