<?php

namespace App\Enums;

enum DocumentType: string
{
    case Invoice = 'invoice';
    case CreditNote = 'credit_note';
    case DebitNote = 'debit_note';
    case Receipt = 'receipt';
    case Estimate = 'estimate';
}

