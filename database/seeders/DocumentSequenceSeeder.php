<?php

namespace Database\Seeders;

use App\Enums\DocumentType;
use App\Models\System\Company;
use App\Models\Tenant\DocumentSequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSequenceSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Company::all()->each(function (Company $company): void {
            $year = (int) now()->year;

            $sequences = [
                [
                    'document_type' => DocumentType::Invoice,
                    'series' => 'A',
                ],
                [
                    'document_type' => DocumentType::CreditNote,
                    'series' => 'B',
                ],
                [
                    'document_type' => DocumentType::DebitNote,
                    'series' => 'C',
                ],
                [
                    'document_type' => DocumentType::Receipt,
                    'series' => 'R',
                ],
                [
                    'document_type' => DocumentType::Estimate,
                    'series' => 'E',
                ],
            ];

            foreach ($sequences as $sequence) {
                DocumentSequence::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'document_type' => $sequence['document_type'],
                        'series' => $sequence['series'],
                        'year' => $year,
                    ],
                    [
                        'current_number' => 1,
                        'format' => '{series}/{year}/{number}',
                        'is_active' => true,
                    ],
                );
            }
        });
    }
}


