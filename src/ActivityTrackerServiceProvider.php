<?php

namespace Mabadir\ActivityTracker;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ActivityTrackerServiceProvider extends ServiceProvider
{
    /**
     * Boot the ServiceProvider
     */
    public function boot()
    {
        Route::prefix('api')
            ->namespace('Mabadir\ActivityTracker\Http\Controllers')
             ->group(__DIR__."/routes/api.php");
    }

    public function register()
    {

    }
}