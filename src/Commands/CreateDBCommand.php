<?php

namespace Wtolk\Adfm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wtolk\Crud\Generator;


class CreateDBCommand extends Command
{

    protected $signature = 'adfm:create_db {name : имя базы данных} {--force : Удалить старую базу с таким же именем}' ;
    protected $description = 'Создает базу данных с заданным именем';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('force')) {
            DB::statement("DROP DATABASE `{$this->argument('name')}`");
            $this->info('Старая база удалена');
        }
        DB::statement("CREATE DATABASE {$this->argument('name')}" );
        $this->info('Новая база '.$this->argument('name').' успешно создана');
    }
}
