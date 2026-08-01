<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_lots', function (Blueprint $table) {
            $table->string('batch_number')->nullable()->after('product_id');
        });

        // Backfill any existing lots with a batch number, oldest-first per product
        // (same order FIFO consumption uses), so numbering reflects real batch order.
        $lots = DB::table('stock_lots')->orderBy('product_id')->orderBy('purchased_at')->orderBy('id')->get();
        $skus = DB::table('products')->pluck('sku', 'id');
        $sequence = [];

        foreach ($lots as $lot) {
            $sku = $skus[$lot->product_id] ?? 'PRODUCT';
            $sequence[$lot->product_id] = ($sequence[$lot->product_id] ?? 0) + 1;

            DB::table('stock_lots')->where('id', $lot->id)->update([
                'batch_number' => sprintf('%s-B%03d', $sku, $sequence[$lot->product_id]),
            ]);
        }

        Schema::table('stock_lots', function (Blueprint $table) {
            $table->string('batch_number')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_lots', function (Blueprint $table) {
            $table->dropUnique(['batch_number']);
            $table->dropColumn('batch_number');
        });
    }
};
