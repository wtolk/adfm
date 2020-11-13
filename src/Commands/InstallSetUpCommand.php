<?php

namespace Wtolk\Adfm\Commands;

use Archetype\Factories\PHPFileFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;



class InstallSetUpCommand extends Command
{

    protected $signature = 'adfm:setup' ;
    protected $description = 'Делает первичную настройку';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $provider = PHPFileFactory::load(app_path('Providers/RouteServiceProvider.php'));
        $provider->constant('HOME', '/pages')->save();
        $this->info('Настроен RouteServiceProvider.php');
    }
}
