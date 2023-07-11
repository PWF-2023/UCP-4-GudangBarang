<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => rand(1, 100),
            'user_id' => User::inRandomOrder()->first()->id,
            // 'category_id' => rand(1, 100),
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => ucwords(fake()->sentence()),
            'is_in' => rand(0, 1)
        ];
    }
}
