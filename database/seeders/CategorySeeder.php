<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Food & Dining',
                'description' => 'Meals, groceries, and beverages',
                'color' => '#EF4444',
                'sort_order' => 1,
                'subs' => [
                    'Groceries' => [
                        'Fresh Produce',
                        'Meat & Fish',
                        'Dairy & Eggs',
                        'Snacks & Beverages',
                    ],
                    'Restaurants' => [
                        'Fast Food',
                        'Fine Dining',
                        'Takeaway',
                    ],
                    'Coffee & Tea' => [],
                    'Work Lunches' => [],
                ],
            ],
            [
                'name' => 'Transportation',
                'description' => 'Getting around',
                'color' => '#3B82F6',
                'sort_order' => 2,
                'subs' => [
                    'Fuel' => [
                        'Petrol',
                        'Diesel',
                    ],
                    'Public Transport' => [
                        'Bus',
                        'Train',
                        'Trotro',
                    ],
                    'Taxi & Ride Share' => [
                        'Uber',
                        'Bolt',
                    ],
                    'Vehicle Maintenance' => [
                        'Repairs',
                        'Servicing',
                        'Tyres',
                    ],
                    'Parking' => [],
                ],
            ],
            [
                'name' => 'Housing',
                'description' => 'Rent, mortgage, and home expenses',
                'color' => '#10B981',
                'sort_order' => 3,
                'subs' => [
                    'Rent' => [],
                    'Mortgage' => [],
                    'Home Repairs' => [
                        'Plumbing',
                        'Electrical',
                        'Painting',
                    ],
                    'Furniture' => [],
                    'Cleaning Supplies' => [],
                ],
            ],
            [
                'name' => 'Utilities',
                'description' => 'Monthly bills and services',
                'color' => '#F59E0B',
                'sort_order' => 4,
                'subs' => [
                    'Electricity' => [],
                    'Water' => [],
                    'Internet' => [],
                    'Phone' => [
                        'Airtime',
                        'Data Bundles',
                    ],
                    'Cable / Streaming' => [],
                ],
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical and health expenses',
                'color' => '#EC4899',
                'sort_order' => 5,
                'subs' => [
                    'Doctor Visits' => [],
                    'Medication' => [
                        'Prescription',
                        'Over the Counter',
                    ],
                    'Dental' => [],
                    'Eye Care' => [],
                    'Health Insurance' => [],
                ],
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Fun and leisure',
                'color' => '#8B5CF6',
                'sort_order' => 6,
                'subs' => [
                    'Movies & Shows' => [],
                    'Music & Concerts' => [],
                    'Sports & Fitness' => [
                        'Gym Membership',
                        'Equipment',
                    ],
                    'Games' => [],
                    'Books & Magazines' => [],
                ],
            ],
            [
                'name' => 'Shopping',
                'description' => 'Clothing, electronics, and more',
                'color' => '#06B6D4',
                'sort_order' => 7,
                'subs' => [
                    'Clothing' => [
                        'Shoes',
                        'Accessories',
                    ],
                    'Electronics' => [
                        'Phones',
                        'Computers',
                        'Accessories',
                    ],
                    'Household Items' => [],
                    'Gifts' => [],
                ],
            ],
            [
                'name' => 'Education',
                'description' => 'Learning and development',
                'color' => '#84CC16',
                'sort_order' => 8,
                'subs' => [
                    'Tuition' => [],
                    'Books & Supplies' => [],
                    'Online Courses' => [],
                    'Workshops & Seminars' => [],
                ],
            ],
            [
                'name' => 'Personal Care',
                'description' => 'Grooming and personal items',
                'color' => '#F97316',
                'sort_order' => 9,
                'subs' => [
                    'Haircuts & Styling' => [],
                    'Skincare' => [],
                    'Toiletries' => [],
                ],
            ],
            [
                'name' => 'Office & Business',
                'description' => 'Work-related expenses',
                'color' => '#6366F1',
                'sort_order' => 10,
                'subs' => [
                    'Office Supplies' => [
                        'Stationery',
                        'Printing',
                    ],
                    'Software & Subscriptions' => [],
                    'Equipment' => [],
                    'Professional Services' => [],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $subs = $categoryData['subs'];
            unset($categoryData['subs']);

            $mainCategory = MainCategory::create($categoryData);

            $sortOrder = 0;
            foreach ($subs as $subName => $children) {
                $sub = SubCategory::create([
                    'name' => $subName,
                    'main_category_id' => $mainCategory->id,
                    'parent_id' => null,
                    'depth' => 0,
                    'sort_order' => $sortOrder++,
                ]);

                $childSortOrder = 0;
                foreach ($children as $childName) {
                    SubCategory::create([
                        'name' => $childName,
                        'main_category_id' => $mainCategory->id,
                        'parent_id' => $sub->id,
                        'depth' => 1,
                        'sort_order' => $childSortOrder++,
                    ]);
                }
            }
        }
    }
}
