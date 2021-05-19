<?php

namespace Database\Factories\Adfm;

use App\Models\Adfm\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'email' => $this->faker->email(),
            'password' => 'test'
        ];
    }
}
