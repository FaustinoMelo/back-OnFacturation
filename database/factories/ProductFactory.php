<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Product\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->word(),
            'description' => $this->faker->sentence(),
            'sku' => $this->faker->unique()->bothify('SKU-????-####'),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'cost_price' => $this->faker->randomFloat(2, 5, 500),
            'is_active' => true,
            'type' => ProductType::Product->value,
        ];
    }
}
