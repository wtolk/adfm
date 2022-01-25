<?php


namespace App\Http\Controllers\Admin\Screens;


use App\Helpers\Adfm\Settings;
use App\Helpers\Dev;
use Whoops\Exception\ErrorException;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\DateTime;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\MultiFile;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\Form\TinyMCE;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\FormPresenter;
use App\Models\Adfm\Land;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Button;
use Spatie\Valuestore\Valuestore;

class SettingsScreen
{
    public $form;
    public $request;

    public function __construct()
    {
        $this->form = new FormPresenter();
        $this->request = request();
    }

    public static function index(Settings $settings)
    {
        $screen = new self();
        $screen->form->isModelExists = false;
        $screen->form->template('form-edit')->source([
            'settings' => $settings->all()
        ]);
        $screen->form->title = 'Настройки';
        $screen->form->route = route('adfm.settings.update');
        $screen->form->columns = self::getFields();
        $screen->form->buttons([
            Button::make('Сохранить')->icon('save')->route('adfm.settings.update')->submit(),
        ]);
        $screen->form->build();
        $screen->form->render();
    }

    public static function getFields() {
        return [
            Column::make([
                Input::make('settings.frontpage.title')->title('Заголовок на главной')
                    ->placeholder('ПРОДАЖА ЗЕРНОВОГО КОФЕ В АБАКАНЕ'),
                Input::make('settings.frontpage.description')->title('Описание на главной')
                    ->placeholder('Насладитесь вкусом свежих и сочных экзотических фруктов, которые мы каждую неделю привозим специально для вас из разных уголков мира!'),
                Input::make('settings.top_phone')->title('телефон в шапке')->placeholder('+7-923-393-3323')
            ]),
        ];
    }
}
