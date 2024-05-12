<?php

use Aldeebhasan\NaiveCrud\Facades\NCRoute;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers\BlogController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.', 'prefix' => 'api'], function () {

    NCRoute::ncResource('blogs', BlogController::class);
    NCRoute::ncResource('comments', CommentController::class);
    NCRoute::files('files');

});
