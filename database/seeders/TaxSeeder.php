<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Modules\Tax\Models\Tax;
use App\Modules\Tax\Models\TaxExemptionReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Cria motivos de isenção padrão para cada empresa
        Company::all()->each(function ($company) {
            TaxExemptionReason::factory(3)->create([
                'company_id' => $company->id,
            ]);

            // Cria impostos para cada empresa
            Tax::factory(8)->create([
                'company_id' => $company->id,
            ]);
        });
    }
}
