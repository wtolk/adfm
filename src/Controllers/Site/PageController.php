<?php

namespace Wtolk\Adfm\Controllers\Site;

use App\Http\Controllers\Controller;
use Wtolk\Adfm\Controllers\Admin\Screens\PageScreen;
use Illuminate\Http\Request;
use Wtolk\Adfm\Models\Page;

class PageController extends Controller
{

    public function showMainPage()
    {
        $page = Page::find(1);
        return view('adfm::public.layout', compact('page'));
    }

}
