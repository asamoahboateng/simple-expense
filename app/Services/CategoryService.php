<?php

namespace App\Services;

use App\Models\SubCategory;

class CategoryService
{
    public static function getSubCategoryOptionsForMainCategory(int $mainCategoryId): array
    {
        $options = [];
        $rootSubs = SubCategory::where('main_category_id', $mainCategoryId)
            ->whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        self::flattenSubCategories($rootSubs, $options, 0);

        return $options;
    }

    private static function flattenSubCategories($subs, array &$options, int $depth): void
    {
        foreach ($subs as $sub) {
            $prefix = str_repeat('— ', $depth);
            $options[$sub->id] = $prefix . $sub->name;
            if ($sub->children->count()) {
                self::flattenSubCategories($sub->children, $options, $depth + 1);
            }
        }
    }
}
