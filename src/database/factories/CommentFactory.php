<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => null, // テスト時に明示的に指定
            'user_id' => null,    // テスト時に明示的に指定
            'content' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
