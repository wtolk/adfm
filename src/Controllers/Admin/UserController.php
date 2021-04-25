<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Screens\UserScreen;
use Illuminate\Http\Request;
use App\Models\Adfm\User;

class UserController extends Controller
{

    public function index()
    {
        UserScreen::index();
    }

    public function create()
    {
        UserScreen::create();
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new User();
        $item->fill($request->all()['user'])->save();
        $item->syncRoles($request->role);
        return redirect()->route('adfm.users.index');
    }

    /**
     * Форма редактирования
     */
    public function edit($id)
    {
        UserScreen::edit();
    }

    /**
     * Обновление
     */
    public function update(Request $request, $id)
    {
        $item = User::findOrFail($id);
        $item->fill($request->all()['user'])->save();
        $item->syncRoles($request->role);
        return redirect()->route('adfm.users.index');
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('adfm.users.index');
    }
}
