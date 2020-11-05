<?php

namespace Wtolk\Adfm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wtolk\Crud\Generator;


class SetEnvCommand extends Command
{

    protected $signature = 'adfm:set_env {key : ключ конфига} {value : значение конфига}' ;
    protected $description = 'Задает настройки в конфиге';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \DotenvEditor::setKey($this->argument('key'), $this->argument('value'));
        \DotenvEditor::save();
    }
}
