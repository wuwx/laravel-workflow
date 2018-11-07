<?php

namespace Wuwx\LaravelWorkflow\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Wuwx\LaravelWorkflow\Http\Controllers';

    public function boot()
    {
        Route::middleware('web')->namespace($this->namespace)->group(__DIR__.'/../../routes/web.php');
    }
}
