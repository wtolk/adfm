<?php

namespace Wtolk\Adfm\Providers;


use App\Helpers\Dev;
use Illuminate\Support\ServiceProvider;


class AdfmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // publish config file

        }

        \View::share('php_tags', '<?php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'adfm');
        //
    }
}
