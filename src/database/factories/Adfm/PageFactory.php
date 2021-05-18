<?php

namespace Database\Factories\Adfm;

use App\Models\Adfm\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->regexify('[A-Z]{9}'),
            'slug' => null,
            'content' => $this->faker->realText(rand(1000, 4000)),
            'options' => ["editor_dev_mode" => "0"],
            'meta' => [
                "title" => null,
                "description" => null
            ],
        ];
    }
}
