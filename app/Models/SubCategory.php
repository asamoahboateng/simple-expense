<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'main_category_id',
        'parent_id',
        'depth',
        'sort_order',
    ];

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    public function allParents(): BelongsTo
    {
        return $this->parent()->with('allParents');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getAllDescendantIds(): array
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }
        return $ids;
    }

    protected function breadcrumb(): Attribute
    {
        return Attribute::get(function () {
            $parts = [$this->name];
            $current = $this;
            while ($current->parent) {
                $current = $current->parent;
                array_unshift($parts, $current->name);
            }
            if ($this->mainCategory) {
                array_unshift($parts, $this->mainCategory->name);
            }
            return implode(' > ', $parts);
        });
    }

    protected static function booted(): void
    {
        static::saving(function (SubCategory $category) {
            if ($category->parent_id) {
                $parent = SubCategory::find($category->parent_id);
                $category->depth = ($parent?->depth ?? 0) + 1;
            } else {
                $category->depth = 0;
            }
        });
    }
}
