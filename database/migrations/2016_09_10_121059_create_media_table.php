<?php

use App\Models\Media;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('path')->unique();
            $table->string('mime');
            $table->string('extension');
            $table->timestamps();
        });


        ImportTable::import('skytz_images', function ($image) {
            Media::createFromFile($image->imagepath, config('skytz.upload_images'));
        });

        ImportTable::import('skytz_docs', function ($document) {
            Media::createFromFile($document->docpath, config('skytz.upload_docs'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }

}
