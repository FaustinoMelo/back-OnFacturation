<?php

namespace App\Services\Invoice;

class TaxCalculationService
{
    public function calculateItemTotal(array $item): array
    {
        $quantity = (float) $item['quantity'];
        $unitPrice = (float) $item['unit_price'];
        $discountPercentage = (float) ($item['discount_percentage'] ?? 0);
        $taxRate = (float) ($item['tax_rate'] ?? 0);

        // Calcular subtotal do item
        $itemSubtotal = $quantity * $unitPrice;

        // Aplicar desconto
        $discountAmount = $itemSubtotal * ($discountPercentage / 100);
        $itemSubtotalAfterDiscount = $itemSubtotal - $discountAmount;

        // Calcular imposto
        $taxAmount = $itemSubtotalAfterDiscount * ($taxRate / 100);

        // Total do item
        $total = $itemSubtotalAfterDiscount + $taxAmount;

        return [
            'subtotal' => round($itemSubtotalAfterDiscount, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
        ];
    }

    public function calculateTotals(array $items): array
    {
        $subtotal = 0;
        $taxTotal = 0;
        $discountTotal = 0;

        foreach ($items as $item) {
            $itemTotals = $this->calculateItemTotal($item);
            $subtotal += $itemTotals['subtotal'];
            $taxTotal += $itemTotals['tax_amount'];
            $discountTotal += $itemTotals['discount_amount'];
        }

        return [
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'discount_total' => round($discountTotal, 2),
            'total' => round($subtotal + $taxTotal, 2),
        ];
    }
}

