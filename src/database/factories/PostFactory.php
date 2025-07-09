<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicFaker = FakerFactory::create('ar_SA'); // Saudi Arabic

        $content = [
            $arabicFaker->realText(150),
            "## {$arabicFaker->realText(40)}",
            $arabicFaker->realText(300),
            "## {$arabicFaker->realText(35)}",
            $arabicFaker->realText(200),
            "### {$arabicFaker->realText(50)}",
            $arabicFaker->realText(300),
            "### {$arabicFaker->realText(25)}",
            $arabicFaker->realText(200),
            "## {$arabicFaker->realText(50)}",
            $arabicFaker->realText(300),
        ];

        return [
            'title' => $arabicFaker->realText(22), // Arabic title
            'slug' => $this->faker->slug(),
            'content' => [[
                'type' => 'markdown',
                'data' => ['content' => implode("\n\n", $content)],
            ]],
            //add fillter as metadata
            //  'metadata' => [
            //      [
            //         'key' => 'category',
            //          'value' => $arabicFaker->city(),
            //      ],
            //  ],
            'user_id' => 1,
            'is_published' => true,//$this->faker->boolean(75),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'post_category_id' => $this->faker->randomElement([1, 2, 3, 4]),
        ];
    }
}

