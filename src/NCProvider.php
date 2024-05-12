<?php

namespace Aldeebhasan\NaiveCrud;

use Aldeebhasan\NaiveCrud\Logic\Managers\RouteManager;
use Illuminate\Support\ServiceProvider;

class NCProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind('NCRoute', RouteManager::class);
        $this->publishes([
            __DIR__.'/../config/naive-crud.php' => config_path('naive-crud.php'),
            __DIR__.'/../lang' => lang_path('/vendor/NaiveCrud'),
        ], 'naive-crud');

    }

    public function register(): void
    {

        $this->mergeConfigFrom(__DIR__.'/../config/naive-crud.php', 'naive-crud');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'NaiveCrud');

    }
}
