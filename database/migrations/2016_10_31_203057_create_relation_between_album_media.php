<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Media;
use App\Models\Album;

class CreateRelationBetweenAlbumMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_media', function (Blueprint $table) {
            $table->integer('album_id')->unsigned();
            $table->foreign('album_id')->references('id')->on('albums');
            $table->integer('media_id')->unsigned();
            $table->foreign('media_id')->references('id')->on('media');
            $table->unique(array('media_id', 'album_id'));
        });

        ImportTable::import('skytz_albumimages', function ($image) {
            $albumImage = Media::createFromFile($image->serverpath, config('skytz.upload_album_images'));
            if ($albumImage) {
                $album = Album::find($image->albumid);
                $album->images()->save($albumImage);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('album_media');
    }
}