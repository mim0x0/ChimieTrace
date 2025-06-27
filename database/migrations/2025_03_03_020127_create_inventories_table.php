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

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('chemical_id')->constrained();

            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('packaging_type')->nullable();
            $table->decimal('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->integer('min_container')->nullable();
            $table->decimal('min_quantity')->nullable();
            $table->string('status')->nullable()->default('sealed');
            $table->date('acq_at')->nullable();
            $table->date('exp_at')->nullable();
            $table->string('add_by')->nullable();
            $table->string('brand')->nullable();
            $table->string('notes')->nullable();
            $table->string('serial_number');
            $table->integer('container_number');


            $table->string('buy_status')->nullable();

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
