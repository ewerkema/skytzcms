<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Event;
use Image;
use Input;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'name', 'description', 'path', 'mime', 'extension', 'folder_id',
    ];

    protected $appends = ['thumbnail_url', 'original_url'];

    public function getThumbnailUrlAttribute()
    {
        return $this->photo_url('thumbnail');
    }

    public function getOriginalUrlAttribute()
    {
        return $this->photo_url('original');
    }

    public function sliders()
    {
        return $this->belongsToMany('App\Models\Slider');
    }

    public function albums()
    {
        return $this->belongsToMany('App\Models\Album');
    }

    public function pages()
    {
        return $this->hasMany('App\Models\Page', 'header_id');
    }

    public function folder()
    {
        return $this->belongsTo('App\Models\Folder');
    }

    /**
     * Scope a query to only include media without folder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutFolder($query)
    {
        return $query->whereNull('folder_id');
    }

    /**
     * Import existing media from old table by copying the file from
     * $origin to $destination.
     *
     * @param $origin
     * @param $destination
     * @return void
     *
     * @deprecated
     */
    public static function createFromFile($origin, $destination)
    {
        $originPath = base_path(Media::fixForwardSlash($origin));
        $destinationPath = public_path($destination);
        $fileName = File::name($origin).'.'.File::extension($origin);

        if (!File::exists($originPath)) {
            echo "File doesn't exist at location \"$originPath\".\n";
            return;
        }

        if (File::exists($destinationPath.$fileName)) {
            return Media::where('name', '=', File::name($destinationPath.$fileName))->first();
        } else {
            if ($newFile = Media::copyFile($originPath, $destinationPath)) {
                $newFilePath = $destination . File::name($newFile) . '.' . File::extension($newFile);
                $image_resolution = list($width, $height) = getimagesize($newFilePath);

                if($image_resolution[0] > 1024) {
                    Image::make($newFilePath)->resize(1024, 576)->save($newFilePath);
                }
                upload_move($newFilePath,'',$newFilePath,'large');

                if($image_resolution[0] > 150) {
                    Image::make($newFilePath)->fit(150, 150)->save($newFilePath);
                }

                upload_move($newFilePath,'',$newFilePath,'thumbnail');

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
     * Create all media types from input.
     *
     * @deprecated
     */
    public function createMedia($file)
    {
        $target_path = upload_path($file,'','original');

        if (file_exists($target_path)) {
            $filename = substr($file, 0, strrpos($file, '.'));
            $extension = substr($file, strrpos($file, '.') + 1);
            $target_file = $filename.uniqid().'.'.$extension;
        } else {
            $target_file = $file;
        }

        if ($file && file_exists($source_path)) {
            $image_resolution = list($width, $height) = getimagesize($source_path);
            if (File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc') {

                upload_move($file,'',$target_file);

                if($image_resolution[0] > 1024) {
                    Image::make($source_path)->resize(1024, 576)->save($source_path);
                }
                upload_move($file,'',$target_file,'large');

                if($image_resolution[0] > 150) {
                    Image::make($source_path)->fit(150, 150)->save($source_path);
                }
                upload_move($file,'',$target_file,'thumbnail');
            } else{
                upload_move($file,'',$target_file);
            }
        }
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

    /**
     * Set the name attribute and check if the file already exists.
     * If the file already exists, add a unique identifier to the name.
     *
     * If the file is an image, also save the large (1024px wide) and
     * thumbnail version (150px wide) version of the image.
     *
     * @param $file
     */
    public function setNameAttribute($file)
    {
        $source_path = upload_tmp_path($file);
        $target_path = upload_path($file,'','original');

        if (file_exists($target_path)) {
            $filename = substr($file, 0, strrpos($file, '.'));
            $extension = substr($file, strrpos($file, '.') + 1);
            $target_file = $filename.uniqid().'.'.$extension;
        } else {
            $target_file = $file;
        }

        if ($file && file_exists($source_path)) {
            list($width, $height) = getimagesize($source_path);
            if (File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc') {

                if($width > 2500 || $height > 2500) {
                    Image::make($source_path)->resize(2500, 2500, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($source_path);
                }

                upload_move($file,'', $target_file);

                if($width > 1024) {
                    Image::make($source_path)->resize(1024, 576)->save($source_path);
                }
                upload_move($file,'', $target_file,'large');

                if($width > 150) {
                    Image::make($source_path)->fit(150, 150)->save($source_path);
                }
                upload_move($file,'',$target_file,'thumbnail');
            } else{
                upload_move($file,'',$target_file);
            }
        }

        $target_file = str_replace("images/", "", $file);
        $this->attributes['name'] = $target_file;

        if (File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc') {
            $this->path = 'images/'.$target_file;
        } else {
            $this->path = 'docs/'.$target_file;
        }

        @unlink(@$source_path);

        if ($file == '') {
            $this->deleteFile();
            $this->attributes['name'] = "";
        }
    }

    /**
     * Returns the photo url.
     *
     * @param string $type
     * @return bool|string
     */
    public function photo_url($type='original')
    {
        if (!empty($this->name))
            return upload_url($this->name,'',$type);
        elseif (!empty($this->name) && file_exists(tmp_path($this->photo)))
            return tmp_url($this->name);
        else
            return asset('img/advertising.jpg');
    }

    /**
     * Delete the media file from storage.
     */
    public function deleteFile()
    {
        upload_delete($this->name, 'images', array('original','thumbnail','large'));
    }

}


Event::listen('eloquent.deleting:Media', function($model) {
    $model->deleteFile();
});
