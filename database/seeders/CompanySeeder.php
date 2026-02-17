<?php

namespace Database\Seeders;

use App\Enums\SubscriptionPlan;
use App\Models\System\Company;
use App\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$testUser) {
            return;
        }

        // Cria empresa padrão
        $company = Company::create([
            'name' => 'Test Company',
            'tax_id' => '12.345.678/0001-99',
            'email' => 'contact@testcompany.com',
            'phone' => '(11) 3000-0000',
            'address' => 'Rua Teste, 123',
            'city' => 'São Paulo',
            'postal_code' => '01310-100',
            'country' => 'BR',
            'currency' => 'BRL',
            'locale' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
            'subscription_plan' => SubscriptionPlan::Premium,
            'max_users' => 10,
            'is_active' => true,
        ]);

        // Associa usuário à empresa
        $company->users()->attach($testUser->id, [
            'role' => 'admin',
            'is_active' => true,
            'joined_at' => now(),
        ]);

        // Atualiza active_company_id do usuário
        $testUser->update(['active_company_id' => $company->id]);

        // Cria empresas adicionais
        Company::factory(3)->create()->each(function ($comp) use ($testUser) {
            $comp->users()->attach($testUser->id, [
                'role' => 'operator',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        });
    }
}
