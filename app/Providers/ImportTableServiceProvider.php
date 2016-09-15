<?php

namespace App\Providers;

use App\Models\ImportTable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ImportTableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('ImportTable', function() {
            return new ImportTable();
        });
    }
}
