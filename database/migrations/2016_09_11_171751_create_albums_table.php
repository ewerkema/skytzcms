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

        if (config('skytz.old_cms')) {
            $this->importAlbums();
            $this->importAlbumImages();
        }
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

    /**
     * Import existing albums from old table.
     *
     * @return void
     */
    public function importAlbums()
    {
        $albums = DB::table('skytz_albums')->get();
        $albums->each(function ($album) {
            Album::create([
                'name' => $album->albumname,
                'colorbox' => $album->album_colorbox,
            ]);
        });
        Schema::drop('skytz_albums');
    }

    /**
     * Import existing images from old table.
     *
     * @return void
     */
    public function importAlbumImages()
    {
        $images = DB::table('skytz_albumimages')->get();
        $images->each(function($image) {
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
        Schema::drop('skytz_albumimages');
    }
}
