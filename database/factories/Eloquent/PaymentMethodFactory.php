<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'parent_id' => null,
            'name' => $this->faker->company,
            'order' => $this->faker->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}
