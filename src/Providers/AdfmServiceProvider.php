<?php

namespace Wtolk\Adfm\Providers;


use App\Helpers\Dev;
use Illuminate\Support\ServiceProvider;
use Wtolk\Adfm\Commands\CheckDBCommand;
use Wtolk\Adfm\Commands\CreateDBCommand;
use Wtolk\Adfm\Commands\InstallSetUpCommand;
use Wtolk\Adfm\Commands\SetEnvCommand;


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
                CheckDBCommand::class,
                SetEnvCommand::class,
                CreateDBCommand::class,
                InstallSetUpCommand::class,
            ]);
        }
        \Blade::directive('render_tree', function ($expression) {
            return $expression;
        });



        \View::share('php_tags', '<?php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'adfm');
    }
}
