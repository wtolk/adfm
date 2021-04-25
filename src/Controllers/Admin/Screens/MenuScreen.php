<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Helpers\Dev;
use Wtolk\Adfm\Models\MenuItem;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\Form\TreeElements;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\Menu;
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
        $screen->form->template('table-list')->source([
            'menus' => Menu::paginate(50)
        ]);
        $screen->form->title = 'Меню сайта';

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
        $screen->form->template('form-edit')->source([
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
        $screen->form->template('form-edit')->source([
            'menu' => Menu::findOrFail($screen->request->route('id'))
        ]);
//        dd(Menu::findOrFail(1)->links[0]->children);
        $screen->form->title = 'Редактирование menu';
        $screen->form->route = route('adfm.menus.update', $screen->form->source['menu']->id);
        $screen->form->columns = self::getFields();
        $screen->form->columns[1]->fields[0]->field_value = $screen->form->source['menu']->links;
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menus.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.menus.destroy')->canSee($screen->form->isModelExists),
            Link::make('Добавить пункт')->route('adfm.menuitems.create', ['menu_id' => $screen->request->route('id')])
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
                    ->placeholder('Заполняется автоматически')
            ])->class('col col-md-6'),
            Column::make([
                TreeElements::make('menu.links')->title('Пункты меню')->link(function ($model) {
                    echo Link::make('<i class="fas fa fa-pen-square"></i>')->route('adfm.menuitems.edit', ['id' => $model->id])->render();
                })
            ])->class('col col-md-6')
        ];
    }

}
