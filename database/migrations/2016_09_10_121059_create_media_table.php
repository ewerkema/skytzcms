<?php

use App\Models\Media;
use Illuminate\Support\Facades\DB;
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
            $table->integer('album_id')->default(0);
            $table->foreign('album_id')->references('id')->on('albums');
            $table->integer('slider_id')->default(0);
            $table->foreign('slider_id')->references('id')->on('sliders');
            $table->timestamps();
        });

        if (config('skytz.old_cms')) {
            $this->importImages();
            $this->importDocuments();
        }
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

    /**
     * Import existing images from old table.
     *
     * @return void
     */
    public function importImages()
    {
        $images = DB::table('skytz_images')->get();
        $images->each(function($image) {
            Media::createFromFile($image->imagepath, config('skytz.upload_images'));
        });
        Schema::drop('skytz_images');
    }

    /**
     * Import existing documents from old table.
     *
     * @return void
     */
    public function importDocuments()
    {
        $docs = DB::table('skytz_docs')->get();
        $docs->each(function($document) {
            Media::createFromFile($document->docpath, config('skytz.upload_docs'));
        });
        Schema::drop('skytz_docs');
    }
}
