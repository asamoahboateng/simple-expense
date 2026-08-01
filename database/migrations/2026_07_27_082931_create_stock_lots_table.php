<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_remaining');
            $table->decimal('unit_cost', 12, 2);
            $table->string('supplier')->nullable();
            $table->string('note')->nullable();
            $table->date('purchased_at');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['product_id', 'purchased_at', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_lots');
    }
};
