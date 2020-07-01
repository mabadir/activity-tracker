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
        Route::middleware('api')
             ->group(__DIR__."/routes/api.php");
    }
}