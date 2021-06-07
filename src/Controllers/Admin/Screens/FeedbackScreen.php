<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Models\Adfm\FeedbackMessage;
use App\Helpers\Dev;
use Wtolk\Crud\Form\Column;
use Wtolk\Crud\Form\File;
use Wtolk\Crud\Form\InfoTable;
use Wtolk\Crud\Form\Summernote;
use Wtolk\Crud\FormPresenter;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\Checkbox;
use Wtolk\Crud\Form\TableField;
use Wtolk\Crud\Form\Link;
use Wtolk\Crud\Form\Button;


class FeedbackScreen
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
            'feedbacks' => FeedbackMessage::paginate(50)
        ]);
        $screen->form->title = 'Сообщения';
        $screen->form->addField(
            TableField::make('title', 'Сообщение')
                ->link(function ($model) {
                    $fields = $model->fields;
                    $fields = array_values($fields);
                    echo Link::make(reset($fields))->route('adfm.feedbacks.edit', ['id' => $model->id])->render();
            })
        );
        $screen->form->addField(TableField::make('created_at', 'Дата создания'));
        $screen->form->addField(
            TableField::make('delete', 'Операции')
                ->link(function ($model) {
                    echo Link::make('Удалить')->route('adfm.feedbacks.destroy', ['id' => $model->id])->render();
            })
        );
        $screen->form->build();
        $screen->form->render();
    }

    public static function showMessageDetails()
    {
        $screen = new self();
        $screen->form->isModelExists = true;
        $screen->form->template('form-edit')->source([
            'feedback' => FeedbackMessage::findOrFail($screen->request->route('id'))
        ]);
        $screen->form->title = 'Сообщение';
        $screen->form->route = route('adfm.feedbacks.index');
        $screen->form->fields = [InfoTable::make('feedback.fields')->title('Детали сообщения')];
        $screen->form->buttons([
            Button::make('Удалить')->icon('trash')->route('adfm.feedbacks.destroy')->canSee($screen->form->isModelExists),
            Link::make('Назад к списку')->route('adfm.feedbacks.index'),
        ]);
        $screen->form->build();
        $screen->form->render();
    }

}
