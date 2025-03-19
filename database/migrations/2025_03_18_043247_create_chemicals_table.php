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
        Schema::create('chemicals', function (Blueprint $table) {
            $table->id();

            $table->string('chemical_name')->unique();
            $table->string('CAS_number')->unique();
            $table->string('serial_number')->nullable();
            $table->string('SKU')->nullable();
            $table->string('image')->nullable();
            $table->string('chemical_structure')->nullable();
            $table->string('SDS_file')->nullable();
            $table->string('reg_by')->nullable();

            $table->foreignId('user_id')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chemicals');
    }
};
