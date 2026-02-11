<?php

namespace App\Services\Invoice;

use App\Enums\DocumentType;
use App\Models\Tenant\DocumentSequence;
use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    public function generateNextNumber(int $companyId, DocumentType $documentType, \DateTime $date): int
    {
        $year = (int) $date->format('Y');
        $series = 'A'; // Default series, pode ser configurável

        return DB::transaction(function () use ($companyId, $documentType, $series, $year): int {
            $sequence = DocumentSequence::where('company_id', $companyId)
                ->where('document_type', $documentType)
                ->where('series', $series)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = DocumentSequence::create([
                    'company_id' => $companyId,
                    'document_type' => $documentType,
                    'series' => $series,
                    'year' => $year,
                    'current_number' => 1,
                    'format' => '{series}/{year}/{number}',
                    'is_active' => true,
                ]);

                return 1;
            }

            $nextNumber = $sequence->current_number + 1;
            $sequence->increment('current_number');

            return $nextNumber;
        });
    }

    public function formatNumber(string $series, int $year, int $number, string $format = '{series}/{year}/{number}'): string
    {
        return str_replace(
            ['{series}', '{year}', '{number}'],
            [$series, $year, str_pad((string) $number, 4, '0', STR_PAD_LEFT)],
            $format
        );
    }
}

