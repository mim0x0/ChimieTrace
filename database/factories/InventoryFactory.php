<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Chemical;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'chemical_id' => Chemical::factory(),
            'user_id' => User::factory(),
            'serial_number' => $this->faker->unique()->bothify('LB-###'),
            'notes' => $this->faker->sentence,
            'location' => 'Lab A',
            'packaging_type' => 'bottle',
            'quantity' => 100,
            'unit' => 'ml',
            'acq_at' => now(),
            'exp_at' => now()->addYear(),
            'container_number' => 1,
            'min_quantity' => 50,
            'brand' => 'Merck',
            'status' => 'empty',
        ];
    }
}
