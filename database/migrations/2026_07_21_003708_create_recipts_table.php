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
        Schema::create('recipts', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel sales dengan constraint unique (Relasi One-to-One)
            $table->foreignId('sales_id')
                ->unique()
                ->constrained('sales')
                ->cascadeOnDelete();

            $table->string('receipt_number')->unique();

            // Enum tipe receipt
            // Saya tambahkan default('umum') agar mempermudah insert data,
            // kamu bisa menghapus ->default() jika nilai ini wajib dikirim secara eksplisit.
            $table->enum('type', ['umum', 'marketplace'])->default('umum');

            $table->integer('printed_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipts');
    }
};
