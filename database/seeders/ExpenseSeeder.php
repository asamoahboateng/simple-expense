<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $mainCategories = MainCategory::with('subCategories')->get();

        if ($mainCategories->isEmpty()) {
            $this->command->warn('No categories found. Run CategorySeeder first.');
            return;
        }

        // Sample expenses with realistic data
        $expenseData = [
            // Food & Dining
            ['title' => 'Weekly grocery shopping', 'cost' => 150.00, 'category' => 'Food & Dining'],
            ['title' => 'Lunch with colleagues', 'cost' => 45.50, 'category' => 'Food & Dining'],
            ['title' => 'Coffee at cafe', 'cost' => 15.00, 'category' => 'Food & Dining'],
            ['title' => 'Takeaway dinner', 'cost' => 35.00, 'category' => 'Food & Dining'],
            ['title' => 'Birthday dinner', 'cost' => 120.00, 'category' => 'Food & Dining'],
            ['title' => 'Morning snacks', 'cost' => 8.50, 'category' => 'Food & Dining'],
            ['title' => 'Fruits and vegetables', 'cost' => 60.00, 'category' => 'Food & Dining'],

            // Transportation
            ['title' => 'Fuel top-up', 'cost' => 200.00, 'category' => 'Transportation'],
            ['title' => 'Uber to meeting', 'cost' => 25.00, 'category' => 'Transportation'],
            ['title' => 'Monthly bus pass', 'cost' => 80.00, 'category' => 'Transportation'],
            ['title' => 'Parking at mall', 'cost' => 10.00, 'category' => 'Transportation'],
            ['title' => 'Car servicing', 'cost' => 350.00, 'category' => 'Transportation'],
            ['title' => 'Bolt ride home', 'cost' => 18.00, 'category' => 'Transportation'],

            // Housing
            ['title' => 'Monthly rent', 'cost' => 1500.00, 'category' => 'Housing'],
            ['title' => 'Plumber for kitchen sink', 'cost' => 120.00, 'category' => 'Housing'],
            ['title' => 'New curtains', 'cost' => 85.00, 'category' => 'Housing'],
            ['title' => 'Cleaning supplies', 'cost' => 45.00, 'category' => 'Housing'],

            // Utilities
            ['title' => 'Electricity bill - January', 'cost' => 180.00, 'category' => 'Utilities'],
            ['title' => 'Water bill', 'cost' => 50.00, 'category' => 'Utilities'],
            ['title' => 'Internet subscription', 'cost' => 100.00, 'category' => 'Utilities'],
            ['title' => 'Phone data bundle', 'cost' => 30.00, 'category' => 'Utilities'],
            ['title' => 'Airtime recharge', 'cost' => 20.00, 'category' => 'Utilities'],
            ['title' => 'Netflix subscription', 'cost' => 15.00, 'category' => 'Utilities'],

            // Healthcare
            ['title' => 'Doctor consultation', 'cost' => 200.00, 'category' => 'Healthcare'],
            ['title' => 'Prescription medicine', 'cost' => 75.00, 'category' => 'Healthcare'],
            ['title' => 'Dental checkup', 'cost' => 150.00, 'category' => 'Healthcare'],
            ['title' => 'Paracetamol and vitamins', 'cost' => 25.00, 'category' => 'Healthcare'],

            // Entertainment
            ['title' => 'Cinema tickets', 'cost' => 40.00, 'category' => 'Entertainment'],
            ['title' => 'Gym membership renewal', 'cost' => 120.00, 'category' => 'Entertainment'],
            ['title' => 'Book purchase', 'cost' => 35.00, 'category' => 'Entertainment'],
            ['title' => 'Concert tickets', 'cost' => 80.00, 'category' => 'Entertainment'],

            // Shopping
            ['title' => 'New work shirts', 'cost' => 180.00, 'category' => 'Shopping'],
            ['title' => 'Running shoes', 'cost' => 250.00, 'category' => 'Shopping'],
            ['title' => 'Phone charger', 'cost' => 30.00, 'category' => 'Shopping'],
            ['title' => 'Birthday gift for friend', 'cost' => 60.00, 'category' => 'Shopping'],

            // Education
            ['title' => 'Online course - Laravel', 'cost' => 50.00, 'category' => 'Education'],
            ['title' => 'Programming books', 'cost' => 90.00, 'category' => 'Education'],

            // Personal Care
            ['title' => 'Haircut', 'cost' => 30.00, 'category' => 'Personal Care'],
            ['title' => 'Skincare products', 'cost' => 55.00, 'category' => 'Personal Care'],
            ['title' => 'Toiletries restock', 'cost' => 40.00, 'category' => 'Personal Care'],

            // Office & Business
            ['title' => 'Printer paper', 'cost' => 25.00, 'category' => 'Office & Business'],
            ['title' => 'Domain renewal', 'cost' => 15.00, 'category' => 'Office & Business'],
            ['title' => 'Hosting subscription', 'cost' => 30.00, 'category' => 'Office & Business'],
        ];

        $persons = [$user->name, 'Sarah', 'Kwame', 'Ama'];

        foreach ($expenseData as $data) {
            $mainCategory = $mainCategories->firstWhere('name', $data['category']);
            $subCategory = $mainCategory?->subCategories->random();

            Expense::create([
                'title' => $data['title'],
                'description' => fake()->optional(0.5)->sentence(),
                'cost' => $data['cost'],
                'user_id' => $user->id,
                'person' => fake()->randomElement($persons),
                'main_category_id' => $mainCategory?->id,
                'sub_category_id' => $subCategory?->id,
                'expense_date' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);
        }

        // Add some random expenses for additional months
        for ($i = 0; $i < 30; $i++) {
            $mainCategory = $mainCategories->random();
            $subCategory = $mainCategory->subCategories->isNotEmpty()
                ? $mainCategory->subCategories->random()
                : null;

            Expense::create([
                'title' => fake()->randomElement([
                    'Miscellaneous purchase', 'Quick errand', 'Subscription payment',
                    'Supply restock', 'Service fee', 'Emergency expense',
                    'Weekly supplies', 'Monthly payment', 'One-time purchase',
                ]),
                'description' => fake()->optional(0.4)->sentence(),
                'cost' => fake()->randomFloat(2, 5, 300),
                'user_id' => $user->id,
                'person' => fake()->randomElement($persons),
                'main_category_id' => $mainCategory->id,
                'sub_category_id' => $subCategory?->id,
                'expense_date' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }
}
