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
        Schema::create('user_requests', function (Blueprint $table) {
            $table->id();


            // $table->foreignId('chemical_id')->nullable()->constrained()->nullOnDelete();
            // $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // $table->unsignedBigInteger('chemical_id')->nullable();
            // $table->unsignedBigInteger('inventory_id')->nullable();
            // $table->unsignedBigInteger('market_id')->nullable();
            // $table->unsignedBigInteger('respondent_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('type')->nullable();
            $table->string('receiver_type')->nullable();

            $table->text('request')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requests');
    }
};
