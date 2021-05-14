<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Helpers\Dev;
use Whoops\Exception\ErrorException;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\Cropper;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Relation;
use Wtolk\Crud\Form\Select;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\MenuItem;
use App\Models\Adfm\Menu;
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
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function createFromModel()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'menuitem' => new MenuItem()
        ]);
        $screen->form->title = 'Создание menuitem';
        $screen->form->route = route('adfm.menuitems.store');
        $screen->form->columns = self::getFields();
        $screen->setMenuLinkFromModelSource();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.menuitems.update')->submit(),
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
                Relation::make('menuitem.menu')->title('Выберите меню')
                    ->options( Menu::all()->pluck('title', 'id')->toArray())->defaultValue((int) request()->route('menu_id')),
                Cropper::make('menuitem.image')->title('Изображение')->cropSize(350, 250)
            ])
        ];
    }

    /**
     * Берет поля которые обьявлены, и заполняет их данными из модели.
     *
     * @throws ErrorException
     */
    public function setMenuLinkFromModelSource()
    {
        $cols = $this->form->columns;

        $model = '\App\Models\Adfm\\'.$this->request->route('model_name');
        // Проверяем интерфейс модели
        $object = new $model();
        $interfaces = class_implements($object);
        if (!in_array("App\Models\Adfm\ILinkMenu", $interfaces)) {
            throw new ErrorException('Модель должна наследовать интерфейс ILinkMenu, что бы ее добавлять в меню', 500);
        }
        // Проверяем есть ли такая запись
        $object = $model::find($this->request->route('model_id'));
        if (is_null($object)) {
            throw new ErrorException(
                'У модели '.$this->request->route('model_name').'
                не найдена запись с id='.$this->request->route('model_id'), 500);
        }

        if ($cols[0]->fields[0]->field_name == 'menuitem[title]' && $cols[0]->fields[2]->field_name == 'menuitem[link]') {
            $cols[0]->fields[0]->defaultValue($object->getLinkTitle());
            $cols[0]->fields[2]->defaultValue($object->getLinkPath());
        } else {
            throw new ErrorException('В методе MenuItemScreen->setMenuLinkFromModelSource() не верно указаны поля (title, link) для заполнения данными из моделей', 500);
        }

        $cols[0]->fields[] = Input::make('menuitem.model_name')->defaultValue($model)->setType('hidden');
        $cols[0]->fields[] = Input::make('menuitem.model_id')->defaultValue($object->id)->setType('hidden');

    }

}
