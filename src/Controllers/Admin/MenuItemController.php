<?php

namespace App\Adfm\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Adfm\Controllers\Admin\Screens\MenuItemScreen;
use Illuminate\Http\Request;
use App\Adfm\Models\MenuItem;

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

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new MenuItem();
        $item->fill($request->all()['menuitem'])->save();
        return redirect()->route('adfm.menuitems.index');
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
        return redirect()->route('adfm.menuitems.index');
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
        MenuItem::destroy($id);
        return redirect()->route('adfm.menuitems.index');
    }
}