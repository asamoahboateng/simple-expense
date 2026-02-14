<?php

namespace Database\Factories;

use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MainCategoryFactory extends Factory
{
    protected $model = MainCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Food & Dining', 'Transportation', 'Housing', 'Utilities',
                'Healthcare', 'Entertainment', 'Shopping', 'Education',
                'Personal Care', 'Insurance', 'Office Supplies', 'Travel',
            ]),
            'description' => fake()->optional(0.7)->sentence(),
            'icon' => null,
            'color' => fake()->hexColor(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
