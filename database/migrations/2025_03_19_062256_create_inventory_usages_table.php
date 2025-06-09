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
        Schema::create('inventory_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('user_name')->nullable();
            $table->string('chemical_name')->nullable();
            $table->string('chemical_cas')->nullable();
            $table->string('inventory_serial')->nullable();
            $table->decimal('quantity_used')->nullable();
            $table->decimal('quantity_left')->nullable();
            $table->decimal('container_left')->nullable();
            $table->text('reason')->nullable();
            // $table->date('log_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_usages');
    }
};
