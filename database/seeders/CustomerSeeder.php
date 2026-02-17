<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Cria clientes para cada empresa
        Company::all()->each(function ($company) {
            Customer::factory(3)->create([
                'company_id' => $company->id,
            ]);
        });
    }
}
