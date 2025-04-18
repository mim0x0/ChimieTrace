<?php

use App\Models\User;
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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->index();
            $table->foreignId('chemical_id')->index();
            $table->string('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('status')->nullable()->default('sealed');
            $table->string('location')->nullable();
            $table->decimal('quantity')->nullable();
            $table->string('notes')->nullable();
            $table->string('buy_status')->nullable();
            $table->integer('min_container')->nullable();
            $table->decimal('min_quantity')->nullable();
            $table->date('acq_at')->nullable();
            $table->date('exp_at')->nullable();
            $table->string('add_by')->nullable();

            $table->timestamps();

            // $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
