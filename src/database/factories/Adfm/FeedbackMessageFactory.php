<?php

namespace Database\Factories\Adfm;

use App\Models\Adfm\FeedbackMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedbackMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fields' => [
                "Как вас зовут ?" => "Имя",
                "Любые контакты для связи" => "Cвязь",
                "Ваше сообщение" => "Cообщение"
            ],
        ];
    }
}
