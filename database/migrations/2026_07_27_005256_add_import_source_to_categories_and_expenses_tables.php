<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['main_categories', 'sub_categories', 'expenses'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('import_source')->nullable()->after('id');
                $table->unsignedBigInteger('import_source_id')->nullable()->after('import_source');
                $table->index(['import_source', 'import_source_id']);
            });
        }
    }

    public function down(): void
    {
        foreach (['main_categories', 'sub_categories', 'expenses'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['import_source', 'import_source_id']);
                $table->dropColumn(['import_source', 'import_source_id']);
            });
        }
    }
};
