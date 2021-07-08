<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Producer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProducerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        $goodsIds = Goods::pluck('producer_id')->toArray();

        return [
            'id' => $goodsIds ? $faker->unique()->randomElement($goodsIds) : $faker->numberBetween(1, 1000),
            'order_for_promotion' => $faker->numberBetween(1, 10),
            'producer_rank' => $faker->numberBetween(1, 10),
            'name' => $faker->word,
            'ext_id' => $faker->word,
            'text' => $faker->realText(),
            'status' => 'active',
            'attachments' => '',
            'show_background' => $faker->boolean ? 't' : 'f',
            'show_logo' => $faker->boolean ? 't' : 'f',
            'disable_filter_series' => $faker->boolean ? 't' : 'f',
        ];
    }
}
