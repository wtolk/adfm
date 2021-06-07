<?php

namespace Database\Factories\Adfm;

use App\Models\Adfm\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MenuItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'parent_id' => 0,
            'is_published' => 0,
            'position' => 0,
            'link' => $this->faker->word(),
            'model_name' => null,
            'model_id' => null
        ];
    }
}
