<?php

use App\Models\Album;
use App\Models\Media;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('colorbox');
            $table->timestamps();
        });

        ImportTable::import('skytz_albums', function ($album) {
            Album::create([
                'name' => $album->albumname,
                'colorbox' => $album->album_colorbox,
            ]);
        });

        ImportTable::import('skytz_albumimages', function ($image) {
            $albumImage = Media::createFromFile($image->serverpath, config('skytz.upload_album_images'));
            if ($albumImage) {
                try {
                    $album = Album::findOrFail($image->albumid);
                } catch (ModelNotFoundException $e) {
                    dd("Couldn't find album with ID ".$image->albumid.": ".$e->getMessage());
                }
                $albumImage->setAlbum($album);
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
        Schema::drop('albums');
    }

}
