<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('cost', 12, 2);
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('person');
            $table->foreignId('main_category_id')
                ->nullable()
                ->constrained('main_categories')
                ->nullOnDelete();
            $table->foreignId('sub_category_id')
                ->nullable()
                ->constrained('sub_categories')
                ->nullOnDelete();
            $table->date('expense_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'expense_date']);
            $table->index('main_category_id');
            $table->index('sub_category_id');
            $table->index('person');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
