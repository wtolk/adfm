<?php

namespace App\Adfm\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Adfm\Models\Page;

class PageController extends Controller
{

    public function showMainPage()
    {
        $page = Page::find(1);
        return view('adfm::public.layout', compact('page'));
    }

    public function showPage($slug)
    {
        $page = Page::where('slug', '=', $slug)->firstOrFail();
        return view('adfm::public.layout', compact('page'));
    }

}
