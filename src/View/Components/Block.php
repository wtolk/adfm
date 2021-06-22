<?php

namespace App\View\Adfm\Components;

use Illuminate\View\Component;
use App\Models\Adfm\Block as BlockModel;

class Block extends Component
{
    public $block;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($slug)
    {
        $this->block = BlockModel::where('slug', $slug)->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('adfm::components.block');
    }
}
