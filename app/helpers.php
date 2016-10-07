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