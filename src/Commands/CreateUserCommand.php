<?php

namespace Wtolk\Adfm\Commands;

use App\Models\Adfm\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wtolk\Crud\Generator;


class CreateUserCommand extends Command
{

    protected $signature = 'adfm:user {name : Имя пользователя} {email : Адрес почты} {password : пароль}' ;
    protected $description = 'Создает учетную запись админа';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (User::where('email', '=', $this->argument('email') )->first() ) {
            $this->error('Пользователь с таким Email уже существует');
        } else {
            $user = User::create(['name' => $this->argument('name'), 'email' => $this->argument('email'),
                'password' => \Hash::make($this->argument('password')) ]);

            $user->assignRole('root');
            $this->info('Пользователь создан');
        }

    }
}
