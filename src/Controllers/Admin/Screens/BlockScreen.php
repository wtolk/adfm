<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Helpers\Adfm\ImageCache;
use App\Helpers\Dev;
use Spatie\Permission\Models\Role;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\MultiFile;
use Wtolk\Crud\Form\Relation;
use Wtolk\Crud\Form\Select;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\Form\TinyMCE;
use Wtolk\Crud\FormPresenter;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;
use App\Models\Adfm\Block;


class BlockScreen
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
            'blocks' => Block::filter(request()->input('filter'))->paginate(50)
        ]);
        $screen->form->title = 'Блоки';

        $screen->form->filters(self::getFilters());
        $screen->form->addField(
            TableField::make('title', 'Название блока')
                ->link(function ($model) {
                    echo Link::make($model->title)->route('adfm.blocks.edit', ['id' => $model->id])
                        ->render();
                })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('', '')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.blocks.destroy', ['id' => $model->id])->render();
                })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.blocks.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'block' => new Block()
        ]);
        $screen->form->title = 'Создание блока';
        $screen->form->route = route('adfm.blocks.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.blocks.update')->submit(),
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
            'block' => Block::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование блока';
        $screen->form->route = route('adfm.blocks.update', $screen->form->source['block']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.blocks.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.blocks.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFilters() {
        return [
            Input::make('filter.title:like')->title('Название Блока')->setFilter(),
        ];
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('block.title')->title('Название')->required(),
                TinyMCE::make('block.content')->title('Контент блока'),
                MultiFile::make('block.files')->title('файлы')
            ]),
            Column::make([
                Input::make('block.slug')->title('Синоним'),
            ])->class('col col-md-4')
        ];
    }
}
