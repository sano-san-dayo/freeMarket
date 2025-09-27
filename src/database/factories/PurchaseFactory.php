<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,    // テスト時に明示的に指定
            'product_id' => null, // テスト時に明示的に指定
            'payment_method' => $this->faker->numberBetween(1,2),
            'zipcode' => $this->faker->postcode,
            'address' => $this->faker->address,
            'building' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
