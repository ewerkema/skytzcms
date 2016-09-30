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
        Schema::drop('albums');
    }

}
