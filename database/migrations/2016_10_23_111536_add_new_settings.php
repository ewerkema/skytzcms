<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSettings extends Migration
{
    private $newSettings = array(
        'youtube_page' => '',
        'googleplus_page' => '',
        'contact_email' => 'info@domein.nl',
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->newSettings as $name => $value) {
            Setting::create([
                'name' => $name,
                'value' => $value,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->newSettings as $name => $value) {
            $setting = Setting::where('name', '=', $name)->first();

            if ($setting != null)
                $setting->delete();
        }
    }
}