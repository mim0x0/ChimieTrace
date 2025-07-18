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
        Schema::create('bulk_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bid_id')->constrained()->onDelete('cascade');
            $table->string('tier');
            $table->decimal('min_qty')->nullable();
            $table->decimal('price_per_unit')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_prices');
    }
};
