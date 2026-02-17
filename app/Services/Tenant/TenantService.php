<?php

namespace App\Services\Tenant;

use App\Models\System\Company;
use App\Models\Tenant\PaymentTerm;
use App\\Modules\\Tax\\Models\\Tax;
use Illuminate\Support\Facades\DB;

class TenantService
{
    public function initializeCompany(Company $company): void
    {
        DB::transaction(function () use ($company): void {
            // Criar condições de pagamento padrão
            PaymentTerm::create([
                'company_id' => $company->id,
                'name' => 'Imediato',
                'days' => 0,
                'is_default' => true,
                'is_active' => true,
            ]);

            PaymentTerm::create([
                'company_id' => $company->id,
                'name' => '30 Dias',
                'days' => 30,
                'is_default' => false,
                'is_active' => true,
            ]);

            // Criar taxas de imposto padrão
            Tax::create([
                'company_id' => $company->id,
                'name' => 'IVA 23%',
                'rate' => 23.00,
                'tax_type' => \App\Enums\TaxType::Iva,
                'is_default' => true,
                'is_active' => true,
            ]);

            Tax::create([
                'company_id' => $company->id,
                'name' => 'IVA 13%',
                'rate' => 13.00,
                'tax_type' => \App\Enums\TaxType::Iva,
                'is_default' => false,
                'is_active' => true,
            ]);

            Tax::create([
                'company_id' => $company->id,
                'name' => 'IVA 6%',
                'rate' => 6.00,
                'tax_type' => \App\Enums\TaxType::Iva,
                'is_default' => false,
                'is_active' => true,
            ]);
        });
    }
}


