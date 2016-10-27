<?php

use Illuminate\Support\Facades\Route;

    function template_url($url) {
        return url(add_template_path($url));
    }

    function add_template_path($url) {
        return "templates/".config('skytz.template').$url;
    }

    function cms_url($url) {
        $url = ($url[0] == '/') ? $url : '/'.$url;
        $url = 'cms'.$url;
        return url($url);
    }

    function page_url($url) {
        return (is_cms()) ? cms_url($url) : url($url);
    }

    function is_cms() {
        return strpos(Route::getCurrentRoute()->getPath(), "cms") !== false;
    }

    function upload_tmp_path($file) {
      return public_path() . "/tmp/$file";
    }

    function upload_tmp_url($file) {
      return asset("tmp/$file");
    }

    function upload_path($file, $model, $variation=false, $relative=null) {
      
      $use_aws = is_null($relative)? Config::get('aws.use',false) : $relative;
      
      if(File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc')
      {
        $folder = "/images/". ( empty($variation) || $variation =='original' ? $model : "{$model}{$variation}" ); 
       }
      else
      {
      $folder = "/docs/". ( empty($variation) || $variation =='original' ? $model : "{$model}{$variation}" ); 
      }



      if (!$use_aws && !is_array($variation) && !file_exists(public_path().$folder)) {
          umask(0);
          @mkdir(public_path().$folder, 0777, true);
        }
      $target_path = ($use_aws? "" : public_path()). "$folder/$file";
      return $target_path;
    }

    function upload_url($file, $model, $variation=false)  {
      $use_aws = Config::get('aws.use',false);
        if(File::extension($file)!='docx' && File::extension($file)!='pdf' && File::extension($file)!='doc')
          {
            $folder = "/images/". ( empty($variation) || $variation =='original' ? $model : "{$model}{$variation}" ); 
           }
          else
          {
          $folder = "/docs"; 
          }


      if(!empty($file)) {
        if($use_aws)
          return Config::get('aws.host')."$folder/$file";
        else
          return asset("$folder/$file");
      } 
      return false;
    }
    
    function upload_move($file,$model,$variation=false) {
      $use_aws = Config::get('aws.use',false);
      $source = upload_tmp_path($file);
      $target = upload_path($file,$model,$variation);
      if($use_aws) {    
        AWS::get('s3')->putObject(array(
                'Bucket'     => Config::get('aws.bucket'),
                'Key'        => $target,
                'SourceFile' => $source,
                'ACL'    => 'public-read'
            ));
      } else {
         copy($source, $target);
      }
    }

    function upload_delete($file,$model,$variations=false) {
      if(empty($file)) return false;;
      if(is_array($variations)) {
        foreach($variations as $variation){
            $local_path = upload_path($file,$model,$variation,false);
            @unlink($local_path);
        } 
      }
    }
