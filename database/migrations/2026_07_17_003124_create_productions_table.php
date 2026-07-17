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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('production_date');
            $table->decimal('total_cost', 15, 2);
            $table->integer('total_result');
            $table->decimal('hpp', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('estimated_profit', 15, 2);
            $table->text('notes')->nullable();
            $table->string('status')->default('draft')->comment('draft | completed | cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
