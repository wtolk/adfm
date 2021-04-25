<?php

namespace App\Models\Adfm;

interface ILinkMenu
{
    /**
     * @return string возвращает путь ссылки в меню;
     */
    public function getLinkPath();

    /**
     * @return string возвращает текст ссылки
     */
    public function getLinkTitle();

    /**
     * @return mixed Это метод трейта MenuLinkable.
     * Проверяем что трейт используется моделью
     */
    public static function bootMenuLinkable();
}
