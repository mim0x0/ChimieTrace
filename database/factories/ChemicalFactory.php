<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chemical;

class ChemicalFactory extends Factory
{
    protected $model = Chemical::class;

    public function definition(): array
    {
        return [
            'chemical_name' => $this->faker->word,
            'CAS_number' => $this->faker->bothify('###-##-#'),
            'empirical_formula' => $this->faker->regexify('[A-Z]{1,2}[0-9]{0,2}'),
            'ec_number' => $this->faker->bothify('###-###-#'),
            'molecular_weight' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
