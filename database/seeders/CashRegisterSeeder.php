<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Models\Tenant\CashRegister;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Company::all()->each(function (Company $company): void {
            CashRegister::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'name' => 'Caixa Principal',
                ],
                [
                    'location' => 'Sede',
                    'opening_balance' => 0,
                    'current_balance' => 0,
                    'is_active' => true,
                ],
            );
        });
    }
}


