<?php

use App\Models\Setting;
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

        ImportTable::reverseImport('skytz_websettings', function ($settings) {
            $this->importSettings($settings);
        });
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
    public function importSettings($settings)
    {
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
    }

}
