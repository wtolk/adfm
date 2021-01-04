<?php


namespace App\Adfm\Controllers\Admin\Screens;


use App\Helpers\Dev;
use Whoops\Exception\ErrorException;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\MultiFile;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\FormPresenter;
use App\Adfm\Models\Page;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Button;

class PageScreen
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
            'pages' => Page::paginate(50)
        ]);

        $screen->form->addField(
            TableField::make('title', 'Название страницы')
                ->link(function ($model) {
                    echo Link::make($model->title)->route('adfm.pages.edit', ['id' => $model->id])
                        ->render();
                })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('ololo', 'Удалить страницу')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.pages.destroy', ['id' => $model->id])->render();
                })
        );
        $screen->form->buttons([
            Link::make('Добавить')->class('button')->icon('note')->route('adfm.pages.create')
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function create()
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'page' => new Page()
        ]);
        $screen->form->title = 'Создание страницы';
        $screen->form->route = route('adfm.pages.store');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.pages.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.pages.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
                'page' => Page::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование страницы';
        $screen->form->route = route('adfm.pages.update', $screen->form->source['page']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.pages.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.pages.destroy')->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('page.title')
                    ->title('Заголовок страницы')
                    ->required()
                    ->placeholder('Например , контакты организации.'),

                Summernote::make('page.content')->title('Содержимое'),

                File::make('page.image')->title('Изображение') ,

                MultiFile::make('page.files')->title('Прикрепленные документы')
            ]),
            Column::make([
                Input::make('page.slug')
                    ->title('Вид в адресной строке'),

                Input::make('page.meta.title')
                    ->title('TITLE (мета-тег)'),

                Checkbox::make('page.meta.checkbox')->title('Чекбокс'),

                Input::make('page.meta.description')
                    ->title('Description (мета-тег)'),
            ])->class('col col-md-4')
        ];
    }


}
