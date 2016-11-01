<?php

namespace App\Providers;

use App\Models\ImportTable;
use App\Models\Article;
use App\Models\ArticleGroup;
use App\Models\Media;
use App\Models\Setting;
use App\Models\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    private $bindModels = array(
        'ImportTable' => ImportTable::class,
        'Article' => Article::class,
        'ArticleGroup' => ArticleGroup::class,
        'Media' => Media::class,
        'Page' => Page::class,
        'Setting' => Setting::class
    );

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
        foreach ($this->bindModels as $model => $class) {
            App::bind($model, function() use ($class) {
                return new $class();
            });
        }
    }
}
