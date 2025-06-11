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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('chemical_id')->nullable()->constrained()->cascadeOnDelete();

            $table->decimal('quantity_needed')->nullable();
            $table->decimal('stock_needed')->nullable();
            $table->string('packaging_type')->nullable();
            $table->string('unit')->nullable();
            $table->string('notes')->nullable();
            $table->date('deadline')->nullable();
            // $table->string('serial_number')->nullable();
            // $table->string('currency', 1000);
            // $table->integer('stock');
            // $table->enum('offer_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
