<?php

namespace Database\Factories;

use App\Models\System\Company;
use App\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'tax_id' => $this->faker->numerify('##.###.###/####-##'),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'BR',
            'currency' => 'BRL',
            'locale' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
            'subscription_plan' => SubscriptionPlan::Premium,
            'max_users' => $this->faker->numberBetween(5, 50),
            'is_active' => true,
        ];
    }
}
