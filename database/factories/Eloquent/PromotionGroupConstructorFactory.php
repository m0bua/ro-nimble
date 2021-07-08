<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGroupConstructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionGroupConstructorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromotionGroupConstructor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $constructorIds = PromotionConstructor::pluck('id')->toArray();
        $groupIds = Goods::pluck('group_id')->toArray();

        return [
            'constructor_id' => $constructorIds ? $this->faker->randomElement($constructorIds) : $this->faker->numberBetween(1, 1000),
            'group_id' => $groupIds ? $this->faker->randomElement($groupIds) : $this->faker->numberBetween(1, 1000),
        ];
    }
}
