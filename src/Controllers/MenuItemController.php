<?php

namespace Wtolk\Adfm\Controllers;

use App\Helpers\Dev;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Wtolk\Adfm\Controllers\Screens\MenuItemScreen;
use Illuminate\Http\Request;
use Wtolk\Adfm\Models\MenuItem;

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
