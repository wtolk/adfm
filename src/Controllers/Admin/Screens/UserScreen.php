<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Helpers\Dev;
use Spatie\Permission\Models\Role;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Select;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\User;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;


class UserScreen
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
            'users' => User::with('roles')->paginate(50)
        ]);
//        dd(User::with('roles')->paginate(50));
        $screen->form->title = 'Пользователи';
        $screen->form->addField(
            TableField::make('title', 'Название')
                ->link(function ($model) {
                    echo Link::make($model->name)->route('adfm.users.edit', ['id' => $model->id])->render();
            })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(TableField::make('role', 'Роль пользователя'));
        $screen->form->addField(
            TableField::make('delete', 'Операции')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.users.destroy', ['id' => $model->id])->render();
            })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.users.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'user' => new User()
        ]);
        $screen->form->title = 'Создание user';
        $screen->form->route = route('adfm.users.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.users.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.users.destroy')->canSee($screen->form->isModelExists)
        ]);

        $screen->form->build();

        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
            'user' => User::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование user';
        $screen->form->route = route('adfm.users.update', $screen->form->source['user']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.users.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.users.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        $user = User::find(request()->route('id'));
        $role_name = ($user && $user->getFirstRole()) ? $user->getFirstRole()->name : null;
        return [
            Column::make([
                Input::make('user.name')->title('Имя')->placeholder('Иван Петров'),
                Input::make('user.email')->title('Email')->placeholder('ipetrov@mail.ru'),
                Input::make('user.password')->title('Пароль (введите новый пароль, если нужно изменить)')
                    ->defaultValue(''),
                Select::make('role')->options(
                        Role::all()->pluck('name', 'name')->toArray()
                )->title('Роль пользователя')->defaultValue($role_name),
            ])
        ];
    }

}
