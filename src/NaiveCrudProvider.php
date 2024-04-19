<?php

namespace Aldeebhasan\NaiveCrud;

use Illuminate\Support\ServiceProvider;

class NaiveCrudProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/naive-crud.php' => config_path('naive-crud.php'),
            __DIR__.'/../lang' => lang_path('/vendor/NaiveCrud'),
        ], 'naive-crud');

    }

    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../config/naive-crud.php', 'naive-crud');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'NaiveCrud');

    }
}
