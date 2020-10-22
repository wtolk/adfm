<?php

namespace Wtolk\Adfm\Controllers\Screens;

use App\Helpers\Dev;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use Wtolk\Adfm\Models\Menu;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;


class MenuScreen
{
    public $form;
    public $request;

    public function __construct()
    {
        $this->form = new FormPresenter();
        $this->request = request();

    }

    public static function index()
    {
        $screen = new self();
        $screen->form->layout('table-list')->source([
            'menus' => Menu::paginate(50)
        ]);

        $screen->form->addField(
            TableField::make('title', 'Название')
                ->link(function ($model) {
                    echo Link::make($model->title)->route('adfm.menus.edit', ['id' => $model->id])->render();
            })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('delete', 'Операции')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.menus.destroy', ['id' => $model->id])->render();
            })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.menus.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->layout('form-edit')->source([
            'menu' => new Menu()
        ]);
        $screen->form->title = 'Создание menu';
        $screen->form->route = route('adfm.menus.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menus.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.menus.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->layout('form-edit')->source([
            'menu' => Menu::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование menu';
        $screen->form->route = route('adfm.menus.update', $screen->form->source['menu']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menus.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.menus.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('menu.title')
                    ->title('Название Меню')
                    ->placeholder('Например главное меню'),
                Input::make('menu.slug')
                    ->title('Синоним')
                    ->placeholder('Заполняется автоматически'),
            ])
        ];
    }

}
