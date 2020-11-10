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

        $this->publishes([
            __DIR__.'/../Controllers' => app_path('Adfm/Controllers'),
            __DIR__.'/../Models' => app_path('Adfm/Models'),
            __DIR__.'/../views' => app_path('Adfm/views'),
            __DIR__.'/../Helpers' => app_path('Adfm/Helpers'),
//            __DIR__.'/../database/migrations' => app_path('../database/migration'),
        ]);

        \View::share('php_tags', '<?php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../public-routes.php');
        $this->loadViewsFrom(app_path('Adfm/views'), 'adfm');
    }
}
