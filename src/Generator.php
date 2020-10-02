<?php

namespace Wtolk\Adfm;

use Illuminate\Support\Facades\Storage;

class Generator
{

    public static function test()
    {
        return 'sadas';
    }

    public static function makeModel()
    {
        $data = view('adfm::stubs.model')->render();
        Storage::disk('core')->put('app/Models/Test4.php',  $data);
    }
}
