<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGoodsConstructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionGoodsConstructorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromotionGoodsConstructor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $constructorIds = PromotionConstructor::pluck('id')->toArray();
        $goodsIds = Goods::pluck('id')->toArray();

        return [
            'constructor_id' => $constructorIds ? $this->faker->unique()->randomElement($constructorIds) : $this->faker->unique()->numberBetween(1, 1000),
            'goods_id' => $goodsIds ? $this->faker->randomElement($goodsIds) : $this->faker->numberBetween(1, 1000),
        ];
    }
}
