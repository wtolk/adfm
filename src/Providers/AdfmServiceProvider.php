<?php

namespace Wtolk\Adfm\Providers;


use App\Helpers\Dev;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Wtolk\Adfm\Commands\CheckDBCommand;
use Wtolk\Adfm\Commands\CreateDBCommand;
use Wtolk\Adfm\Commands\CreateUserCommand;
use Wtolk\Adfm\Commands\InstallSetUpCommand;
use Wtolk\Adfm\Commands\SetEnvCommand;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;

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
                CreateUserCommand::class
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
            __DIR__.'/../database/migrations' => app_path('../database/migrations'),
        ]);

        \View::share('php_tags', '<?php');
        $this->loadRoutesFrom(__DIR__ . '/../admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../public-routes.php');
        $this->loadViewsFrom(app_path('Adfm/views'), 'adfm');

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('crud::auth.login');
        });
        Fortify::registerView(function () {
            return view('crud::auth.register');
        });
        Fortify::requestPasswordResetLinkView(function () {
            return view('crud::auth.forgot-password');
        });
        Fortify::resetPasswordView(function () {
            return view('crud::auth.reset-password');
        });
    }




}
