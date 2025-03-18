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
            $table->string('chemical_name')->nullable();
            $table->string('CAS_number')->unique();
            $table->string('serial_number')->nullable();
            $table->string('location')->nullable();
            $table->decimal('quantity')->nullable();
            $table->string('SKU')->nullable();
            $table->string('image')->nullable();
            $table->string('chemical_structure')->nullable();
            $table->date('acq_at')->nullable();
            $table->date('exp_at')->nullable();
            $table->string('SDS_file')->nullable();

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
