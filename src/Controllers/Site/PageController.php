<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Adfm\Models\Page;

class PageController extends Controller
{

    public function showMainPage()
    {
        return view('adfm::public.index');
    }

    public function showPage($slug)
    {
        $page = Page::where('slug', '=', $slug)->firstOrFail();
        return view('adfm::public.page', compact('page'));
    }

}
