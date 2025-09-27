<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            [
                'name' => '良好',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'name' => '目立った傷や汚れなし',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'name' => 'やや傷や汚れあり',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'name' => '状態が悪い',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
        ];
    }
}
