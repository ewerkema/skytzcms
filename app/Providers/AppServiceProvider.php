<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\HtmlBlock;
use App\Models\ImportTable;
use App\Models\Album;
use App\Models\Article;
use App\Models\ArticleGroup;
use App\Models\Media;
use App\Models\Module;
use App\Models\Setting;
use App\Models\Page;
use App\Models\Slider;
use App\Models\Form;
use App\Models\Social;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    private $bindModels = array(
        'ImportTable' => ImportTable::class,
        'Article' => Article::class,
        'Album' => Album::class,
        'ArticleGroup' => ArticleGroup::class,
        'Media' => Media::class,
        'Page' => Page::class,
        'Project' => Project::class,
        'ProjectGroup' => ProjectGroup::class,
        'Setting' => Setting::class,
        'Slider' => Slider::class,
        'Module' => Module::class,
        'CustomForm' => Form::class,
        'HtmlBlock' => HtmlBlock::class,
        'Social' => Social::class,
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
        Carbon::setLocale(config('app.locale'));

        foreach ($this->bindModels as $model => $class) {
            App::bind($model, function() use ($class) {
                return new $class();
            });
        }
    }
}
