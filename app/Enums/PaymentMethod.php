<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';
    case Card = 'card';
    case Check = 'check';
    case Mbway = 'mbway';
    case Multibanco = 'multibanco';
}

