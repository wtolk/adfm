<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adfm\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Screens\BlockScreen;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        BlockScreen::index();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        BlockScreen::create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Block();
        $item->fill($request->all()['block'])->save();
        return redirect()->route('adfm.blocks.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adfm\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Block $block)
    {
        BlockScreen::edit();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adfm\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Block::findOrFail($id);
        $item->fill($request->all()['block'])->save();
        return redirect()->route('adfm.blocks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adfm\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Block::destroy($id);
        return redirect()->route('adfm.blocks.index');
    }
}
