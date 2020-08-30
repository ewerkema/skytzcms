<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('swift_identifier')->nullable();
            $table->json('to')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('sender')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->json('from')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->string('charset')->nullable();
            $table->string('content_type')->nullable();
            $table->text('description')->nullable();
            $table->string('format')->nullable();
            $table->string('reply_to')->nullable();
            $table->string('return_path')->nullable();
            $table->timestamp('send_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
