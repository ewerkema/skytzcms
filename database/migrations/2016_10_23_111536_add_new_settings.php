<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSettings extends Migration
{
    private $newSettings = array(
        'facebook_page' => '',
        'twitter_page' => '',
        'youtube_page' => '',
        'googleplus_page' => '',
        'contact_email' => 'info@domein.nl',
        'contact_name_visible' => true,
        'contact_subject_visible' => true,
        'contact_telephone_visible' => true,
        'contact_message_visible' => true,
        'contact_success_message' => "Bedankt voor het versturen van het bericht.",
        'header_image' => false,
        'header_slider' => false,
        'googleanalytics' => '',
        'recordgoogle' => 0,
        'footerblock' => '',
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::createMany($this->newSettings);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::deleteMany($this->newSettings);
    }
}
