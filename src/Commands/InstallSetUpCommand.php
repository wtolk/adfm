<?php

namespace Wtolk\Adfm\Commands;

use App\Adfm\Models\Role;
use Archetype\Factories\PHPFileFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;


class InstallSetUpCommand extends Command
{

    protected $signature = 'adfm:setup' ;
    protected $description = 'Делает первичную настройку';

    public $start_permissions = [
        'page.create', 'page.edit', 'page.delete', 'page.*',
        'menu.create', 'menu.edit', 'menu.delete', 'menu.*',
        'menu_item.create', 'menu_item.edit', 'menu_item.delete', 'menu_item.*',
        'role.create', 'role.edit', 'role.delete', 'role.*'
    ];

    public $start_roles = [
        'root', 'manager'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $provider = PHPFileFactory::load(app_path('Providers/RouteServiceProvider.php'));
        $provider->constant('HOME', '/pages')->save();
        $this->info('Настроен RouteServiceProvider.php');
        $this->createRolesAndPermissionsAndUsers();
        $this->info('Настроены роли и пользователи');
    }

    public function createRolesAndPermissionsAndUsers()
    {
        foreach ($this->start_permissions as $permission_name) {
            Permission::create(['name'=> $permission_name]);
        }

        $permissions = Permission::all();

        foreach ($this->start_roles as $role_name) {
            $role = Role::create(['name' => $role_name]);
            $role->syncPermissions($permissions);
        }
    }
}
