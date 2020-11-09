<?php

namespace App\Adfm\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Adfm\Controllers\Admin\Screens\MenuScreen;
use Illuminate\Http\Request;
use App\Adfm\Models\Menu;
use App\Adfm\Models\MenuItem;

class MenuController extends Controller
{

    public function index()
    {
        MenuScreen::index();
    }

    public function create()
    {
        MenuScreen::create();
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new Menu();
        $item->fill($request->all()['menu'])->save();
        return redirect()->route('adfm.menus.index');
    }

    /**
     * Форма редактирования
     */
    public function edit($id)
    {
        MenuScreen::edit();
    }

    /**
     * Обновление
     */
    public function update(Request $request, $id)
    {
        $item = Menu::findOrFail($id);
        if ($request->exists('links')) {
            MenuItem::syncHierarchy($request->all()['links']);
        }
        $item->fill($request->all()['menu'])->save();
        return redirect()->route('adfm.menus.index');
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
        Menu::destroy($id);
        return redirect()->route('adfm.menus.index');
    }
}
