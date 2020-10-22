<?php

namespace Wtolk\Adfm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Wtolk\Adfm\Models\Page;

class PageFactory extends Factory
{

    protected $model = Page::class;

    public function definition()
    {
        $title = $this->faker->sentence(rand(1, 4), true);
        $txt = $this->faker->realText(rand(1000, 4000));
        $isPublished = rand(1, 5) > 1;
        $createdAt = $this->faker->dateTimeBetween('-3 month', '-2 days');

        return [
            'title' => $title,
            'slug' => Str::slug($title, '-', 'ru'),
            'content' => $txt,
            'is_published' => $isPublished,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ];
    }
}
