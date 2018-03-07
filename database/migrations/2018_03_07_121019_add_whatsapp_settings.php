<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhatsappSettings extends Migration
{
    private $newSettings = array(
        'display_whatsapp' => false,
        'whatsapp_number' => '+316xxxxxxxx',
        'whatsapp_display_number' => '+316 xxx xxx xx',
        'whatsapp_text' => 'Appen met ons? Klik hieronder om te beginnen',
        'whatsapp_timer' => 3,
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
