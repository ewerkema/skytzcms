<?php

namespace App\Providers;

use App\Models\ImportTable;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('ImportTable', function() {
            return new ImportTable();
        });

        App::bind('Media', function() {
            return new Media();
        });

        App::bind('Setting', function() {
            return new Setting();
        });

    }
}
