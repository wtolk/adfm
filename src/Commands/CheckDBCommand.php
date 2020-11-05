<?php

namespace Wtolk\Adfm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wtolk\Crud\Generator;


class CheckDBCommand extends Command
{

    protected $signature = 'adfm:check_db {db : Имя базы данных}' ;
    protected $description = 'Проверяет существует ли база данных';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $db = DB::select($query, [$this->argument('db')]);
        if (empty($db)) {
            $this->info('false');
        } else {
            $this->info('true');
        }
    }
}
