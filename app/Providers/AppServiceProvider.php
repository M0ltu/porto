<?php

namespace App\Providers;

use App\Support\Filesystem;
use App\Support\Portfolio;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->app->singleton('files', fn () => new Filesystem);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', fn ($view) => $view->with('portfolio', Portfolio::data()));
    }
}
