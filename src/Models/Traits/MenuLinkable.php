<?php


namespace App\Models\Adfm\Traits;
use App\Models\Adfm\MenuItem;

trait MenuLinkable
{
    /**
     * вызывает события модели
     */
    public static function bootMenuLinkable()
    {
        // После обновления модели, обновляем ссылку в меню, если найдем
        static::updated(function ($model) {
            $model_name = '\App\Adfm\Models\\'.class_basename($model);
            $link = MenuItem::where('model_name', '=', $model_name)->where('model_id', '=', $model->id)->get()->first();
            if ($link) {
                $link->link = $model->getLinkPath();
                $link->save();
            }
        });

        // После удаления модели, удаляем ссылку в меню, если найдем
        static::deleted(function ($model) {
            $model_name = '\App\Adfm\Models\\'.class_basename($model);
            $link = MenuItem::where('model_name', '=', $model_name)->where('model_id', '=', $model->id)->get()->first();
            if ($link) {
                $link->delete();
            }
        });
    }
}
