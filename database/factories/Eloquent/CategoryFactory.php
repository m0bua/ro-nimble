<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'mpath' => '.',
            'status' => 'active',
            'status_inherited' => 'active',
            'order' => $this->faker->numberBetween(1, 10),
            'ext_id' => null,
            'titles_mode' => $this->faker->word(),
            'kits_show' => $this->faker->word(),
            'parent_id' => null,
            'left_key' => null,
            'right_key' => null,
            'level' => $this->faker->numberBetween(1, 5),
            'sections_list' => $this->faker->words(2, true),
            'href' => $this->faker->url(),
            'rz_mpath' => '.',
            'allow_index_three_parameters' => $this->faker->boolean() ? 't' : 'f',
            'on_subdomain' => $this->faker->word(),
            'oversized' => $this->faker->word(),
            'is_subdomain' => $this->faker->boolean() ? 't' : 'f',
            'disable_kit_ratio' => $this->faker->boolean() ? 't' : 'f',
            'is_rozetka_top' => $this->faker->boolean() ? 't' : 'f',
        ];
    }
}
