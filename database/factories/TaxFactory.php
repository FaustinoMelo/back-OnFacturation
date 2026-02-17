<?php

namespace Database\Factories;

use App\Enums\TaxType;
use App\Modules\Tax\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Tax\Models\Tax>
 */
class TaxFactory extends Factory
{
    protected $model = Tax::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Tax',
            'rate' => $this->faker->randomFloat(4, 0, 50),
            'tax_type' => $this->faker->randomElement([TaxType::Iva->value, TaxType::Iss->value, TaxType::Icms->value, TaxType::Ipi->value, TaxType::Other->value]),
            'is_active' => true,
        ];
    }
}
