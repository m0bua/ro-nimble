<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\OptionSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'category_id' => $this->faker->numberBetween(1, 1000),
            'option_id' => (string)$this->faker->numberBetween(1, 1000),
            'order' => $this->faker->numberBetween(1, 10),
            'print_order' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->word(),
            'in_short_description' => $this->faker->boolean() ? 't' : 'f',
            'is_comparable' => $this->faker->boolean() ? 't' : 'f',
            'show_selected_filter_title' => $this->faker->boolean() ? 't' : 'f',
            'option_to_print' => $this->faker->boolean() ? 't' : 'f',
            'is_searchable' => $this->faker->boolean() ? 't' : 'f',
            'comment' => $this->faker->word(),
            'template' => $this->faker->word(),
            'comparable' => $this->faker->word(),
            'weight' => $this->faker->randomFloat(2),
            'strict_equal_similars' => $this->faker->boolean() ? 't' : 'f',
            'hide_block_in_filter' => $this->faker->boolean() ? 't' : 'f',
            'special_combobox_view' => $this->faker->word(),
            'disallow_import_filters_orders' => $this->faker->boolean() ? 't' : 'f',
            'number_template' => $this->faker->word(),
            'get_from_standard' => $this->faker->boolean() ? 't' : 'f',
        ];
    }
}
