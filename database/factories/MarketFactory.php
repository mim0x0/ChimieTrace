<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chemical;
use App\Models\Inventory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'chemical_id' => Chemical::factory(),
            'inventory_id' => Inventory::factory(),
            'stock_needed' => $this->faker->numberBetween(100, 1000),
            'quantity_needed' => $this->faker->numberBetween(100, 1000),
            'packaging_type' => 'bottle',
            'unit' => 'ml',
            'notes' => $this->faker->sentence(),
        ];
    }

}
