<?php

namespace App\View\Adfm\Components;

use Illuminate\View\Component;
use Wtolk\Crud\Form\Input;
use Wtolk\Crud\Form\TextArea;

class Feedback extends Component
{
    public $fields = [];
    public $id;

    public function __construct($id)
    {
        $this->id = $id;

        $this->fields = [
            Input::make('Как вас зовут ?')->title('Как вас зовут ?')->required()->placeholder('Иван Иванов'),
            Input::make('Любые контакты для связи')->title('Любые контакты для связи')->required()->placeholder('Телефон или почта'),
            TextArea::make('Ваше сообщение')->title('Ваше сообщение')->placeholder('Суть вашего вопроса'),
        ];
    }

    public function render()
    {
        return view('adfm::components.feedback');
    }
}
