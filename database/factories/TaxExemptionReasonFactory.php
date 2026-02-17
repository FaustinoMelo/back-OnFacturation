<?php

namespace Database\Factories;

use App\Modules\Tax\Models\TaxExemptionReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Tax\Models\TaxExemptionReason>
 */
class TaxExemptionReasonFactory extends Factory
{
    protected $model = TaxExemptionReason::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('EXE-####'),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
