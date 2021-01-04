<?php

namespace App\Adfm\Controllers\Admin\Screens;

use App\Helpers\Dev;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\Cropper;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Relation;
use Wtolk\Crud\Form\Select;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use App\Adfm\Models\MenuItem;
use App\Adfm\Models\Menu;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;


class MenuItemScreen
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
            'menuitems' => MenuItem::paginate(50)
        ]);

        $screen->form->addField(
            TableField::make('title', 'Название')
                ->link(function ($model) {
                    echo Link::make($model->title)->route('adfm.menuitems.edit', ['id' => $model->id])->render();
            })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('delete', 'Операции')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.menuitems.destroy', ['id' => $model->id])->render();
            })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.menuitems.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'menuitem' => new MenuItem()
        ]);
        $screen->form->title = 'Создание menuitem';
        $screen->form->route = route('adfm.menuitems.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menuitems.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.menuitems.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
            'menuitem' => MenuItem::findOrFail($screen->request->route('id'))
        ]);
        $item = MenuItem::findOrFail($screen->request->route('id'));



        $screen->form->title = 'Редактирование menuitem';
        $screen->form->route = route('adfm.menuitems.update', $screen->form->source['menuitem']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menuitems.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.menuitems.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('menuitem.title')
                    ->title('Текст пункта меню')
                    ->placeholder('Например, главная'),
                Checkbox::make('menuitem.is_published')->title('Опубликованно'),
                Input::make('menuitem.link')
                    ->title('Ссылка')
                    ->placeholder('http://google.ru'),
                Select::make('menuitem.select')->options([
                    '1' => 'Быть',
                    '0' => 'Не быть'
                    ]
                )->title('Вопрос')->empty('Нет выбора'),
                Relation::make('menuitem.menu')->title('Выберите меню')
                    ->options( Menu::all()->pluck('title', 'id')->toArray()),
                Cropper::make('menuitem.image')->title('Изображение')
            ])
        ];
    }

}
