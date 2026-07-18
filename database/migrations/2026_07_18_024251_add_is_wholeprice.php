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
        if (! Schema::hasColumn('products', 'is_wholeprice')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_wholeprice')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'is_wholeprice')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_wholeprice');
            });
        }
    }
};
