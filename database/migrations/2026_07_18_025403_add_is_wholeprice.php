<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('sale_items', 'is_wholeprice')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->boolean('is_wholeprice')->default(false)->after('price');
                $table->foreignId('wholeprice_id')->nullable()->after('is_wholeprice')->constrained('product_wholeprices')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sale_items', 'is_wholeprice')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropForeign(['wholeprice_id']);
                $table->dropColumn(['is_wholeprice', 'wholeprice_id']);
            });
        }
    }
};
