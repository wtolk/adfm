<?php

Route::get('/olololo', function () {

    $data = view('adfm::stubs.model')->render();
//    \App\Helpers\Dev::dd($data);
    \App\Helpers\Dev::dd('pack', $data);
//    Storage::disk('core')->put('app/Models/Test3.php',  $data);

});
