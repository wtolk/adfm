<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Screens\PageScreen;
use Illuminate\Http\Request;
use App\Models\Adfm\Page;

class PageController extends Controller
{

    public function index()
    {
        PageScreen::index();
    }

    public function create()
    {
        PageScreen::create();
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $item = new Page();
        $item->fill($request->all()['page'])->save();
        return redirect()->route('adfm.pages.index');
    }

    /**
     * Форма редактирования
     */
    public function edit($id)
    {
        PageScreen::edit();
    }

    /**
     * Обновление
     */
    public function update(Request $request, $id)
    {
        $item = Page::findOrFail($id);
        $item->fill($request->all()['page'])->save();
        return redirect()->route('adfm.pages.index');
    }

    /**
     * Удаляем в корзину
     */
    public function destroy($id)
    {
       Page::destroy($id);
       return redirect()->route('adfm.pages.index');
    }
}
