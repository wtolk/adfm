<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Helpers\Dev;
use Spatie\Permission\Models\Permission;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\Custom;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\Role;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;


class RoleScreen
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
            'roles' => Role::paginate(50)
        ]);
//        dd(Role::paginate(50));
        $screen->form->addField(
            TableField::make('name', 'Название')
                ->link(function ($model) {
                    echo Link::make($model->name)->route('adfm.roles.edit', ['id' => $model->id])->render();
            })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('delete', 'Операции')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.roles.destroy', ['id' => $model->id])->render();
            })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.roles.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'role' => new Role()
        ]);
        $screen->form->title = 'Создание role';
        $screen->form->route = route('adfm.roles.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.roles.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.roles.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
            'role' => Role::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование role';
        $screen->form->route = route('adfm.roles.update', $screen->form->source['role']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.roles.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.roles.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('role.name')
                    ->title('Название поля')
                    ->placeholder('Текст заглушки'),
                Input::make('role.guard_name')
                    ->title('Название поля')
                    ->placeholder('Текст заглушки'),
            ])->class('col col-md-6'),
            Column::make([
                Custom::template('adfm::permissions')->vars([
                    'role' => Role::findOrFail(request()->route('id')),
                    'permissions' => Permission::all()
                ])
            ])->class('col col-md-6')
        ];
    }

}
