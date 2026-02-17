<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Models\Tenant\PaymentTerm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTermSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Company::all()->each(function (Company $company): void {
            $terms = [
                [
                    'name' => 'À vista',
                    'days' => 0,
                    'description' => 'Pagamento imediato na data de emissão.',
                    'is_default' => true,
                ],
                [
                    'name' => '30 dias',
                    'days' => 30,
                    'description' => 'Pagamento 30 dias após a emissão.',
                    'is_default' => false,
                ],
                [
                    'name' => '60 dias',
                    'days' => 60,
                    'description' => 'Pagamento 60 dias após a emissão.',
                    'is_default' => false,
                ],
            ];

            foreach ($terms as $term) {
                PaymentTerm::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'name' => $term['name'],
                    ],
                    [
                        'days' => $term['days'],
                        'description' => $term['description'],
                        'is_default' => $term['is_default'],
                        'is_active' => true,
                    ],
                );
            }
        });
    }
}


