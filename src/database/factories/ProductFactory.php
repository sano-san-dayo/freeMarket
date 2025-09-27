<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'user_id' => null, // テスト時に明示的に指定
            'name' => $this->faker->word,
            'brand' => $this->faker->word,
            'condition' => $this->faker->numberBetween(1,4),
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
