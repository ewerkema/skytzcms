<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'name', 'description', 'path', 'mime', 'extension',
    ];

    public function slider()
    {
        return $this->belongsTo('App\Models\Slider');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\Album');
    }

    /**
     * Import existing media from old table.
     *
     * @param $origin
     * @param $destination
     * @return void|static
     */
    public static function createFromFile($origin, $destination)
    {
        $originPath = base_path(Media::fixForwardSlash($origin));
        $destinationPath = public_path($destination);

        if (!File::exists($originPath)) {
            echo "File doesn't exist at location \"$originPath\".\n";
            return;
        }

        if ($newFile = Media::copyFile($originPath, $destinationPath)) {
            $newFilePath = $destination . File::name($newFile) . '.' . File::extension($newFile);
            return Media::create([
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
     * @param $path
     * @return string
     */
    public static function fixForwardSlash($path)
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
    public static function copyFile($path, $newDirectory)
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
