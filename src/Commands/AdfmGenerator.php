<?php

namespace Wtolk\Adfm\Commands;

use Illuminate\Console\Command;
use Wtolk\Adfm\Generator;


class AdfmGenerator extends Command
{

    protected $signature = 'adfm:make {entity}';
    protected $description = 'Генерирует сущность (Модель, Контроллер, Экран)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Генерируем сущность...');
        Generator::makeModel();
    }
}
