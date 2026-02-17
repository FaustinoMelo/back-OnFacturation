<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Tenant\DocumentSequence;
use App\Modules\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Invoice\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function configure(): static
    {
        return $this->afterMaking(function (Invoice $invoice) {
            if ($invoice->company_id && !$invoice->sequence_id) {
                // Get or create sequence
                $sequence = DocumentSequence::firstOrCreate(
                    [
                        'company_id' => $invoice->company_id,
                        'document_type' => 'invoice',
                        'series' => 'INV',
                        'year' => now()->year,
                    ],
                    [
                        'series' => 'INV',
                        'year' => now()->year,
                        'current_number' => 1,
                    ]
                );

                // Get next number and update sequence
                $nextNumber = $sequence->current_number;
                $sequence->increment('current_number');

                $invoice->sequence_id = $sequence->id;
                $invoice->number = $nextNumber;
                $invoice->full_number = str_replace(
                    ['{series}', '{year}', '{number}'],
                    [$sequence->series, $sequence->year, $nextNumber],
                    $sequence->format
                );
            }
        });
    }

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement([InvoiceType::Normal->value, InvoiceType::Simplified->value, InvoiceType::Export->value]),
            'status' => InvoiceStatus::Draft->value,
            'date' => $this->faker->dateTime(),
            'due_date' => $this->faker->dateTime(),
            'subtotal' => $this->faker->randomFloat(2, 100, 10000),
            'total' => $this->faker->randomFloat(2, 100, 10000),
            'paid_amount' => 0,
            'notes' => $this->faker->paragraph(),
        ];
    }
}
