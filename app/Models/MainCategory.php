<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'sort_order',
        'import_source',
        'import_source_id',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public function rootSubCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class)->whereNull('parent_id');
    }

    public function rootSubCategoriesWithChildren(): HasMany
    {
        return $this->rootSubCategories()->with('allChildren');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
