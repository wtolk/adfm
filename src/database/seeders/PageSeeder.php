<?php

namespace Wtolk\Adfm\Database\Seeders;

use Illuminate\Database\Seeder;
use Wtolk\Adfm\Models\Page;

class PageSeeder extends Seeder
{


    public function run()
    {
        Page::factory(10)->create();
    }
}
