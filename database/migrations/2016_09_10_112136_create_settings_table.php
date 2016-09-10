<?php

use App\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value');
        });

        if (config('skytz.old_cms'))
            $this->importSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }

    /**
     * Import settings from old database.
     *
     * @return void
     */
    public function importSettings()
    {
        $settings = DB::table('skytz_websettings')->first();
        foreach ($settings as $name => $setting) {
            if ($name == 'id')
                continue;

            if ($name == 'redirict') // Fix typo
                $name = 'redirect';

            Setting::create([
                'name' => $name,
                'value' => $setting
            ]);
        }
        Schema::drop('skytz_websettings');
    }

}
