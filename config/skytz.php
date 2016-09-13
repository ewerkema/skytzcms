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
];