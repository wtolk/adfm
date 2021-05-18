<?php

namespace Wtolk\Adfm\Providers;

use App\Helpers\Adfm\ImageCache;
use App\Helpers\Dev;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
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
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Aws\S3\S3Client;
use Aws\Laravel\AwsServiceProvider;

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
        $this->registerYandexStorageDriver();
        $this->registerConsoleCommands();
        $this->setViewHelpers();

        $this->publishes([
            __DIR__.'/../Controllers' => app_path('Http/Controllers'),
            __DIR__.'/../Models' => app_path('Models/Adfm'),
            __DIR__.'/../views' => resource_path('views/adfm'),
            __DIR__.'/../Helpers' => app_path('Helpers/Adfm'),
            __DIR__.'/../routes' => base_path('/routes/adfm'),
            __DIR__.'/../View' => app_path('View/Adfm'),
            __DIR__.'/../database/migrations' => app_path('../database/migrations'),
            __DIR__.'/../database/factories' => app_path('../database/factories'),
            __DIR__.'/../tests' => base_path('/tests'),
        ]);

        if (file_exists(base_path('/routes/adfm') . '/admin-routes.php')) {
            $this->loadRoutesFrom(base_path('/routes/adfm') . '/admin-routes.php');
            $this->loadRoutesFrom(base_path('/routes/adfm') . '/public-routes.php');
        } else {
            $this->loadRoutesFrom(__DIR__.'/../routes' . '/admin-routes.php');
            $this->loadRoutesFrom(__DIR__.'/../routes' . '/public-routes.php');
        }

        $this->loadViewsFrom(resource_path('views/adfm'), 'adfm');

        $this->registerFortifySettings();
        $this->setSuperAdminRole();

    }

    /**
     * Добавляет новое хранилище в Яндекс Облаке
     */
    protected function registerYandexStorageDriver()
    {
        Storage::extend('yandexcloud', function($app, $config) {
            $client = new S3Client([
                'credentials' => [
                    'key'    => env('YANDEX_STORAGE_ACCESS_KEY_ID'),
                    'secret' => env('YANDEX_STORAGE_SECRET_ACCESS_KEY'),
                ],
                'region' => 'ru-central1',
                'version' => 'latest',
                'endpoint' => 'http://storage.yandexcloud.net/',

            ]);

            return new Filesystem(new AwsS3Adapter($client, env('YANDEX_STORAGE_BUCKET'), env('YANDEX_STORAGE_FOLDER')));
        });
        config(['filesystems.disks.yandex-cloud' => [
            'driver' => 'yandexcloud',
        ]]);
    }

    /**
     * Создает пути авторизации и вьюхи для функционала Fortify
     */
    protected function registerFortifySettings()
    {

        RateLimiter::for("login", function () {
            Limit::perMinute(15);
        });

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

    /**
     * Создает роль на которую не действуют ограничение прав
     */
    protected function setSuperAdminRole()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('root') ? true : null;
        });
    }

    /**
     * Создает роль на которую не действуют ограничение прав
     */
    protected function setViewHelpers()
    {
        Blade::if('FrontPage', function () {
            return Request::is('/');
        });
        \View::share('php_tags', '<?php');

        if (!App::runningInConsole()) {
            class_alias(ImageCache::class, 'ImageCache');
        }
    }

    /**
     * Регистрирует консольные комманды
     */
    protected function registerConsoleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckDBCommand::class,
                SetEnvCommand::class,
                CreateDBCommand::class,
                InstallSetUpCommand::class,
                CreateUserCommand::class
            ]);
        }
    }



}
