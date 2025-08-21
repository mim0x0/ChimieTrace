<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Market;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // supplier
            'market_id' => Market::factory(),
            'price' => $this->faker->numberBetween(100, 500),
            'quantity' => $this->faker->numberBetween(10, 100),
            'stock' => $this->faker->numberBetween(50, 200),
            'notes' => $this->faker->sentence,
        ];
    }

}
