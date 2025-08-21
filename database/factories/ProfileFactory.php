<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = \App\Models\Profile::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'postal' => $this->faker->postcode(),
            'profile_pic' => null,
            'status' => 'Active',
            'score' => 0,
        ];
    }
}
