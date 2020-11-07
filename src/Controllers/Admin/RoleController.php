<?php

namespace Wtolk\Adfm\Controllers\Admin;

use App\Http\Controllers\Controller;
use Wtolk\Adfm\Controllers\Admin\Screens\RoleScreen;
use Illuminate\Http\Request;
use Wtolk\Adfm\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
//        phpinfo();
//        die;
//        \Illuminate\Database\Schema\MySqlBuilder::class;
//        \Schema::setConnection();
        RoleScreen::index();
    }

    public function create()
    {
        RoleScreen::create();
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new Role();
        $item->fill($request->all()['role'])->save();
        return redirect()->route('adfm.roles.index');
    }

    /**
     * Форма редактирования
     */
    public function edit($id)
    {
        RoleScreen::edit();
    }

    /**
     * Обновление
     */
    public function update(Request $request, $id)
    {
        $item = Role::findOrFail($id);
        $item->syncPermissions($request->all()['permissions']);
        $item->fill($request->all()['role'])->save();
        return redirect()->route('adfm.roles.index');
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
        Role::destroy($id);
        return redirect()->route('adfm.roles.index');
    }
}
