<?php

namespace Database\Seeders;

use App\Models\System\Company;
use App\Models\Tenant\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Company::all()->each(function (Company $company): void {
            $categories = [
                [
                    'name' => 'Serviços',
                    'description' => 'Serviços prestados pela empresa.',
                ],
                [
                    'name' => 'Produtos',
                    'description' => 'Produtos físicos ou digitais vendidos.',
                ],
                [
                    'name' => 'Assinaturas',
                    'description' => 'Planos de assinatura e recorrência.',
                ],
            ];

            foreach ($categories as $category) {
                Category::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'name' => $category['name'],
                    ],
                    [
                        'description' => $category['description'],
                        'slug' => Str::slug($category['name']),
                        'is_active' => true,
                    ],
                );
            }
        });
    }
}


