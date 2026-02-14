<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'cost',
        'user_id',
        'person',
        'main_category_id',
        'sub_category_id',
        'expense_date',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Expense $expense) {
            if ($expense->sub_category_id) {
                $subCategory = SubCategory::find($expense->sub_category_id);
                if ($subCategory) {
                    $expense->main_category_id = $subCategory->main_category_id;
                }
            }
        });
    }
}
