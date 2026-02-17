<?php

namespace Database\Factories;

use App\Enums\CustomerType;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Customer\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement([CustomerType::Individual->value, CustomerType::Company->value]);
        
        return [
            'name' => $type === 'individual' ? $this->faker->name() : $this->faker->company(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'tax_id' => $this->faker->numerify('###.###.###-##'),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'BR',
            'customer_type' => $type,
            'is_active' => true,
        ];
    }
}
