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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->index()->cascadeOnDelete();

            $table->string('status')->nullable();
            $table->string('score')->nullable();
            $table->string('profile_pic')->nullable();

            // $table->string('company_name')->nullable();
            $table->string('phone_number')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal')->nullable();
            $table->timestamps();

            // $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
