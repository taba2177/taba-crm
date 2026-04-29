<?php

namespace Taba\Crm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Taba\Crm\Models\PostCategory;

/**
 * @extends Factory<PostCategory>
 */
class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'slug'               => $this->faker->unique()->slug(),
            'name'               => ['ar' => $name, 'en' => $name],
            'register_in_header' => false,
            'HEAVY_SECTION'      => false,
            'order'              => 0,
        ];
    }
}
