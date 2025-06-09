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
        Schema::create('chemical_properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chemical_id')->constrained()->onDelete('cascade');
            $table->string('color')->nullable();
            $table->string('physical_state')->nullable(); // solid, liquid, gas
            $table->float('melting_point')->nullable(); // °C
            $table->float('boiling_point')->nullable(); // °C
            $table->string('flammability')->nullable(); // e.g., flammable, non-flammable
            $table->text('other_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chemical_properties');
    }
};
