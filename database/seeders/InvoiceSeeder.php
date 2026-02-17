<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Modules\Customer\Models\Customer;
use App\Modules\Invoice\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Cria faturas para cada empresa
        Company::all()->each(function ($company) {
            // Pega clientes da empresa
            $customers = Customer::where('company_id', $company->id)->get();
            
            // Se não há clientes, cria alguns
            if ($customers->isEmpty()) {
                $customers = Customer::factory(5)->create(['company_id' => $company->id]);
            }

            // Cria faturas associadas aos clientes da empresa
            $customers->each(function ($customer) use ($company) {
                Invoice::factory(3)->create([
                    'company_id' => $company->id,
                    'customer_id' => $customer->id,
                ]);
            });
        });
    }
}

