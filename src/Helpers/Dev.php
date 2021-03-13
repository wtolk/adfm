<?php

namespace App\Helpers\Adfm;

class Dev
{
    static function dd(...$var)
    {
        unset(\Kint::$plugins[5], \Kint::$plugins[10], \Kint::$plugins[9]);
//        dd(\Kint::$plugins);
        d($var);
        die;
    }
}
