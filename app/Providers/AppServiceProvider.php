<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // PATCH: https://laravel.com/docs/master/migrations#indexes
        Schema::defaultStringLength(191);

        if (env('APP_DEBUG')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function ($view) {
            $channels = \Cache::rememberForever('channels', function() {
                return \App\Channel::all();
            });
            $view->with('channels', $channels);
        });
        // \View::share('channels', \App\Channel::all());
    }
}
