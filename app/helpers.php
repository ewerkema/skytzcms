<?php

    function template_url($file) {
        return url(add_template_path($file));
    }

    function add_template_path($file) {
        return "templates/".config('skytz.template').$file;
    }

    function cms_url($file) {
        return url('cms'.$file);
    }