<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Screens\MenuItemScreen;
use Illuminate\Http\Request;
use App\Models\Adfm\MenuItem;

class MenuItemController extends Controller
{

    public function index()
    {
        MenuItemScreen::index();
    }

    public function create()
    {
        MenuItemScreen::create();
    }

    public function createFromModel()
    {
        MenuItemScreen::createFromModel();
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new MenuItem();
        $item->fill($request->all()['menuitem'])->save();
        return redirect()->route('adfm.menus.edit', ['id' => $item->menu_id]);
    }

    /**
     * Форма редактирования
     */
    public function edit($id)
    {
        MenuItemScreen::edit();
    }

    /**
     * Обновление
     */
    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $item->fill($request->all()['menuitem'])->save();
        return redirect()->route('adfm.menus.edit', ['id' => $item->menu_id]);
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        MenuItem::destroy($id);
        return redirect()->route('adfm.menus.edit', ['id' => $item->menu_id]);
    }
}
