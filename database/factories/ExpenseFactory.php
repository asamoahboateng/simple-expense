<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $titles = [
            'Lunch at restaurant', 'Taxi ride', 'Monthly rent', 'Electric bill',
            'Doctor visit', 'Movie tickets', 'New shoes', 'Textbook purchase',
            'Haircut', 'Car insurance', 'Printer paper', 'Flight booking',
            'Grocery shopping', 'Bus fare', 'Water bill', 'Prescription medicine',
            'Streaming subscription', 'Phone case', 'Online course', 'Gym membership',
            'Coffee', 'Parking fee', 'Internet bill', 'Dental checkup',
            'Concert tickets', 'Backpack', 'Workshop fee', 'Spa treatment',
            'Gas/Fuel', 'Home repair', 'Phone bill', 'Eye exam',
        ];

        return [
            'title' => fake()->randomElement($titles),
            'description' => fake()->optional(0.6)->sentence(),
            'cost' => fake()->randomFloat(2, 1, 500),
            'user_id' => User::factory(),
            'person' => fake()->name(),
            'main_category_id' => null,
            'sub_category_id' => null,
            'expense_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
