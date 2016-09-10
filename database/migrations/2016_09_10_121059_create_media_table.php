<?php

use App\Media;
use Illuminate\Support\Facades\File;
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
            $table->string('path')->unique();
            $table->string('mime');
            $table->string('extension');
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
            $this->createMediaWithOldFile($image->imagepath, config('skytz.upload_images'));
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
            $this->createMediaWithOldFile($document->docpath, config('skytz.upload_docs'));
        });
        Schema::drop('skytz_docs');
    }

    /**
     * Import existing media from old table.
     *
     * @param $origin
     * @param $destination
     */
    public function createMediaWithOldFile($origin, $destination)
    {
        $originPath = base_path($this->fixForwardSlash($origin));
        $destinationPath = public_path($destination);

        if (!File::exists($originPath)) {
            echo "File doesn't exist at location \"$originPath\".\n";
            return;
        }

        if ($newFile = $this->copyFile($originPath, $destinationPath)) {
            $newFilePath = $destination . File::name($newFile) . '.' . File::extension($newFile);
            Media::create([
                'name' => File::name($newFile),
                'path' => $newFilePath,
                'mime' => File::mimeType($newFile),
                'extension' => File::extension($newFile),
            ]);
        } else {
            echo "Couldn't copy file to location \"$destinationPath\".\n";
        }
    }

    /**
     * Fix path by removing forward slash at the front.
     *
     * @return string
     */
    public function fixForwardSlash($path)
    {
        return ltrim($path, '/');
    }

    /**
     * Copy file to directory, and make sure directory exists.
     * Returns the new directory if the copy was successful.
     *
     * @param $path string
     * @param $newDirectory string
     * @return string
     */
    public function copyFile($path, $newDirectory)
    {
        $fileName = File::name($path).'.'.File::extension($path);
        $newPath = $newDirectory.$fileName;

        if (!File::isDirectory($newDirectory))
            File::makeDirectory($newDirectory, 0775, true);

        if (!File::copy($path, $newPath)) {
            echo "Could not move file from \"$path\" to \"".$newPath."\".\n";
            return false;
        }

        return $newPath;
    }
}
