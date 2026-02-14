<?php

namespace Database\Factories;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubCategoryFactory extends Factory
{
    protected $model = SubCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->optional(0.5)->sentence(),
            'main_category_id' => MainCategory::factory(),
            'parent_id' => null,
            'depth' => 0,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function childOf(SubCategory $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'main_category_id' => $parent->main_category_id,
            'depth' => $parent->depth + 1,
        ]);
    }
}
