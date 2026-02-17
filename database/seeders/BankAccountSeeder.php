<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Models\Tenant\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Company::all()->each(function (Company $company): void {
            $currency = $company->currency ?? 'EUR';

            BankAccount::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'name' => 'Conta Principal',
                ],
                [
                    'bank_name' => 'Banco Padrão',
                    'account_number' => '0001-000000-0',
                    'iban' => null,
                    'swift' => null,
                    'currency' => $currency,
                    'is_active' => true,
                    'is_default' => true,
                ],
            );

            BankAccount::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'name' => 'Conta Secundária',
                ],
                [
                    'bank_name' => 'Banco Secundário',
                    'account_number' => '0002-000000-0',
                    'iban' => null,
                    'swift' => null,
                    'currency' => $currency,
                    'is_active' => true,
                    'is_default' => false,
                ],
            );
        });
    }
}


