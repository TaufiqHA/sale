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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel sales dengan constraint unique (Relasi One-to-One)
            $table->foreignId('sales_id')
                ->unique()
                ->constrained('sales')
                ->cascadeOnDelete();

            $table->string('invoice_number')->unique();

            // Enum dengan opsi 'umum'.
            // Saya tambahkan default('umum') agar otomatis terisi jika tidak didefinisikan saat create.
            $table->enum('type', ['umum'])->default('umum');

            $table->integer('printed_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
