<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MigrationImportService
{
    public static function import(array $payload, string $sourceHost): array
    {
        $summary = [
            'users' => 0,
            'main_categories' => 0,
            'sub_categories' => 0,
            'expenses' => 0,
        ];

        DB::transaction(function () use ($payload, $sourceHost, &$summary) {
            $userMap = self::importUsers($payload['users'] ?? [], $summary);
            $mainCategoryMap = self::importMainCategories($payload['main_categories'] ?? [], $sourceHost, $summary);
            $subCategoryMap = self::importSubCategories($payload['sub_categories'] ?? [], $sourceHost, $mainCategoryMap, $summary);
            self::importExpenses($payload['expenses'] ?? [], $sourceHost, $userMap, $mainCategoryMap, $subCategoryMap, $summary);
        });

        return $summary;
    }

    private static function importUsers(array $rows, array &$summary): array
    {
        $map = [];

        foreach ($rows as $row) {
            $user = User::updateOrCreate(
                ['email' => $row['email']],
                ['name' => $row['name'], 'password' => $row['password']],
            );
            $map[$row['id']] = $user->id;
            $summary['users']++;
        }

        return $map;
    }

    private static function importMainCategories(array $rows, string $sourceHost, array &$summary): array
    {
        $map = [];

        foreach ($rows as $row) {
            $category = MainCategory::updateOrCreate(
                ['import_source' => $sourceHost, 'import_source_id' => $row['id']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'icon' => $row['icon'] ?? null,
                    'color' => $row['color'] ?? null,
                    'sort_order' => $row['sort_order'] ?? 0,
                ],
            );
            $map[$row['id']] = $category->id;
            $summary['main_categories']++;
        }

        return $map;
    }

    private static function importSubCategories(array $rows, string $sourceHost, array $mainCategoryMap, array &$summary): array
    {
        $map = [];

        usort($rows, fn ($a, $b) => ($a['depth'] ?? 0) <=> ($b['depth'] ?? 0));

        foreach ($rows as $row) {
            if (! isset($mainCategoryMap[$row['main_category_id']])) {
                continue;
            }

            $category = SubCategory::updateOrCreate(
                ['import_source' => $sourceHost, 'import_source_id' => $row['id']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'main_category_id' => $mainCategoryMap[$row['main_category_id']],
                    'parent_id' => $row['parent_id'] ? ($map[$row['parent_id']] ?? null) : null,
                    'sort_order' => $row['sort_order'] ?? 0,
                ],
            );
            $map[$row['id']] = $category->id;
            $summary['sub_categories']++;
        }

        return $map;
    }

    private static function importExpenses(array $rows, string $sourceHost, array $userMap, array $mainCategoryMap, array $subCategoryMap, array &$summary): void
    {
        foreach ($rows as $row) {
            if (! isset($userMap[$row['user_id']])) {
                continue;
            }

            Expense::updateOrCreate(
                ['import_source' => $sourceHost, 'import_source_id' => $row['id']],
                [
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                    'cost' => $row['cost'],
                    'user_id' => $userMap[$row['user_id']],
                    'person' => $row['person'],
                    'main_category_id' => $row['main_category_id'] ? ($mainCategoryMap[$row['main_category_id']] ?? null) : null,
                    'sub_category_id' => $row['sub_category_id'] ? ($subCategoryMap[$row['sub_category_id']] ?? null) : null,
                    'expense_date' => $row['expense_date'],
                ],
            );
            $summary['expenses']++;
        }
    }
}
