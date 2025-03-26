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

            $table->foreignId('user_id')->index();
            $table->foreignId('inventory_id')->nullable();
            $table->foreignId('chemical_id')->index();
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 10)->default('RM');
            $table->integer('stock');
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
