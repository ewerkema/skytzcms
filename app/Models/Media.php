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


     public function setNameAttribute($file) {

        $source_path = upload_tmp_path($file);
        $image_resolution=list($width, $height) = getimagesize($source_path);
         
        if ($file && file_exists($source_path)) 
        {
           if(File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc'){  
                
                    upload_move($file,'');
                
                    if($image_resolution[0]>1024)   
                    {
                        Image::make($source_path)->resize(1024, 576)->save($source_path);
                    }
                        upload_move($file,'','large');

                        Image::make($source_path)->resize(150, 150)->save($source_path);
                        upload_move($file,'','thumbnail');
            }
            else{
                upload_move($file,'');
            }
            @unlink($source_path);
            $this->deleteFile();
        }
        $this->attributes['name'] = $file;
        if ($file == '') 
        {
            $this->deleteFile();
            $this->attributes['name'] = "";

        }
    }
    public function photo_url($type='original') 
    {
        if (!empty($this->name))
            return upload_url($this->name,'',$type);
        elseif (!empty($this->name) && file_exists(tmp_path($this->photo)))
            return tmp_url($this->name);
        else
            return asset('img/advertising.jpg');
    }
    public function deleteFile() 
    {
        upload_delete($this->name,'images',array('original','thumbnail','large'));
    }

}
Event::listen('eloquent.deleting:Photo', function($model) {
    $model->deleteFile();
});
