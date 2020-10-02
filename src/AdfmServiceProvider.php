<?php

namespace Wtolk\Adfm;


use Illuminate\Support\ServiceProvider;
use Wtolk\Adfm\Commands\AdfmGenerator;


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

            $this->commands([
                AdfmGenerator::class,
            ]);
        }

        \View::share('php_tags', '<?php');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'adfm');
        //
    }
}
