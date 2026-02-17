<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Cria produtos para cada empresa
        Company::all()->each(function ($company) {
            Product::factory(3)->create([
                'company_id' => $company->id,
            ]);
        });
    }
}
