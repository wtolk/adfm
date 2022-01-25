<?php


namespace App\Helpers\Adfm;
use Illuminate\Support\Arr;
use Spatie\Valuestore\Valuestore;

class Settings extends Valuestore
{
    public static function g($key)
    {
        $settings = app()->get(self::class)->all();
        return Arr::get($settings, $key);
    }
}
