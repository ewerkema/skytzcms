<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Old Database File
    |--------------------------------------------------------------------------
    |
    | This value contains the link to the old database file, which is used to
    | translate it to the new database using migrations.
    */

    'old_cms' => env('SKYTZ_OLD_CMS', 'database/skytzcms_old.sql'),

    /*
    |--------------------------------------------------------------------------
    | Template
    |--------------------------------------------------------------------------
    |
    | Enter here the name of the template. The files that are located within
    | public/templates/{template}/ are loaded to the views that are located
    | in resources/templates/{template}/.
    */

    'template' => env('SKYTZ_TEMPLATE', 'demosite'),

    /*
    |--------------------------------------------------------------------------
    | Upload directory images
    |--------------------------------------------------------------------------
    |
    | This value determines the upload directory to which images
    | are uploaded to.
    */

    'upload_images' => env('SKYTZ_UPLOAD_IMAGES', 'images\\'),

    /*
    |--------------------------------------------------------------------------
    | Upload directory album images
    |--------------------------------------------------------------------------
    |
    | This value determines the upload directory to which images
    | are uploaded to.
    */

    'upload_album_images' => env('SKYTZ_UPLOAD_ALBUM_IMAGES', 'images\albums\\'),

    /*
   |--------------------------------------------------------------------------
   | Upload directory slider images
   |--------------------------------------------------------------------------
   |
   | This value determines the upload directory to which images
   | are uploaded to.
   */

    'upload_slider_images' => env('SKYTZ_UPLOAD_SLIDER_IMAGES', 'images\sliders\\'),

    /*
    |--------------------------------------------------------------------------
    | Upload directory documents
    |--------------------------------------------------------------------------
    |
    | This value determines the upload directory to which documents
    | are uploaded to.
    */

    'upload_docs' => env('SKYTZ_UPLOAD_DOCS', 'docs\\'),

    /*
    |--------------------------------------------------------------------------
    | Header width
    |--------------------------------------------------------------------------
    |
    | The default width of the header, which is used in the crop editor
    | for header images.
    */

    'header_width' => env('SKYTZ_HEADER_WIDTH', 900),

    /*
    |--------------------------------------------------------------------------
    | Header height
    |--------------------------------------------------------------------------
    |
    | The default height of the header, which is used in the crop editor
    | for header images.
    */

    'header_height' => env('SKYTZ_HEADER_HEIGHT', 300),
];