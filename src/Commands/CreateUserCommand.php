<?php

namespace Wtolk\Adfm\Commands;

use App\Models\Adfm\User;
use App\Models\Adfm\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wtolk\Crud\Generator;

class CreateUserCommand extends Command
{
    protected $signature = 'adfm:user {name?} {email?} {password?}' ;
    protected $description = 'Создает учетную запись админа';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Получаем аргументы (если переданы)
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Если аргументы не переданы - запрашиваем их интерактивно
        if (!$name) {
            $name = $this->ask('Введите имя пользователя');
            while (empty($name)) {
                $name = $this->ask('Имя пользователя обязательно. Пожалуйста, введите имя');
            }
        }

        if (!$email) {
            $email = $this->ask('Введите email пользователя');
            while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email = $this->ask('Некорректный формат email. Пожалуйста, введите корректный email');
            }
        } else {
            // Валидация email при передаче аргументом
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Некорректный формат email');
                return 1;
            }
        }

        if (!$password) {
            $password = $this->secret('Введите пароль пользователя');
            while (empty($password)) {
                $password = $this->secret('Пароль обязателен. Пожалуйста, введите пароль');
            }
        }

        // Проверяем существование пользователя с таким email
        if (User::where('email', '=', $email)->first()) {
            $this->error('Пользователь с таким Email уже существует');
            return 1;
        }

        // Получаем список доступных ролей
        $roles = Role::pluck('name')->toArray();
        
        // Если нет ролей, создаем роль root
        if (empty($roles)) {
            Role::firstOrCreate(['guard_name' => 'web'], ['name' => 'root']);
            $roles = ['root'];
        }

        // Выбор роли
        $role = $this->choice('Выберите роль для пользователя', $roles, 'root');

        // Создаем пользователя
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        // Назначаем роль
        $user->assignRole($role);
        
        $this->info('Пользователь создан успешно!');
        return 0;
    }
}
