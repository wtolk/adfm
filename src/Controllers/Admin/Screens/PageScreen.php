<?php


namespace App\Http\Controllers\Admin\Screens;


use App\Helpers\Dev;
use Whoops\Exception\ErrorException;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\DateTime;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\MultiFile;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\Page;
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
            'pages' => Page::withTrashed()->filter(request()->input('filter'))->paginate(50)
        ]);
        $screen->form->title = 'Страницы';
        $screen->form->addField(
            TableField::make('title', 'Название страницы')
                ->link(function ($model) {
                    echo Link::make($model->title)->route('adfm.pages.edit', ['id' => $model->id])
                        ->render();
                })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('', '')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.pages.destroy', ['id' => $model->id])->render();
                })
        );
        $screen->form->addField(
            TableField::make('', '')
                ->link(function ($model) {
                    echo Link::make('Просмотр')->route('adfm.show.page', ['slug' => $model->slug])->render();
                })
        );
        $screen->form->filters(self::getFilters());

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
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function edit()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
                'page' => Page::find($screen->request->route('id'))
        ]);
        $screen->form->title = 'Редактирование страницы';
        $screen->form->route = route('adfm.pages.update', $screen->form->source['page']->id);
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.pages.update')->submit(),
            Button::make('Удалить')->icon('trash')->route('adfm.pages.destroy')->canSee($screen->form->isModelExists),
            Link::make('Добавить в меню')->icon('trash')
                ->route('adfm.menuitems.createFromModel', [
                    'model_name' => 'Page',
                    'model_id' => $screen->request->route('id'),
                    'menu_id' => '0',
                ])->canSee($screen->form->isModelExists)
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFilters() {
        return [
            Input::make('filter.title:like')->title('Заголовок страницы')->setFilter(),
            Input::make('filter.content:like')->title('Текст страницы')->setFilter(),
        ];
    }

    public static function getFields() {
        $page = Page::find(request()->route('id'));
        $dev_mode = false;
        if (isset($page) && $page->options['editor_dev_mode'] == 1) {
            $dev_mode = true;
        }

        return [
            Column::make([
                Input::make('page.title')
                    ->title('Заголовок страницы')
                    ->required()
                    ->placeholder('Например , контакты организации.'),
                Checkbox::make('page.options.editor_dev_mode')->title('Режим разработчика'),
                Summernote::make('page.content')->title('Содержимое')->devMode($dev_mode),

//                File::make('page.image')->title('Изображение') ,

                MultiFile::make('page.files')->title('Прикрепленные документы')
            ]),
            Column::make([
                Input::make('page.slug')
                    ->title('Вид в адресной строке'),

                Input::make('page.meta.title')
                    ->title('TITLE (мета-тег)'),

//                Checkbox::make('page.meta.checkbox')->title('Чекбокс'),

                Input::make('page.meta.description')
                    ->title('Description (мета-тег)'),

//                DateTime::make('time')->title('Time')
            ])->class('col col-md-4')
        ];
    }
}
