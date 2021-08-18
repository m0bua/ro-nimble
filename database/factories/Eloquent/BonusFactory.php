<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Bonus;
use App\Models\Eloquent\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

class BonusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bonus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'goods_id' => $this->faker->unique()->randomElement(Goods::query()->pluck('id')->toArray()),
            'comment_bonus_charge' => $this->faker->numberBetween(1, 10),
            'comment_photo_bonus_charge' => $this->faker->numberBetween(1, 10),
            'comment_video_bonus_charge' => $this->faker->numberBetween(1, 10),
            'bonus_not_allowed_pcs' => $this->faker->boolean() ? 't' : 'f',
            'comment_video_child_bonus_charge' => $this->faker->numberBetween(1, 10),
            'bonus_charge_pcs' => $this->faker->numberBetween(1, 10),
            'use_instant_bonus' => $this->faker->boolean() ? 't' : 'f',
            'premium_bonus_charge_pcs' => $this->faker->numberBetween(1, 10),
        ];
    }
}
