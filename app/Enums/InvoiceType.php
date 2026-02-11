<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Normal = 'normal';
    case Simplified = 'simplified';
    case Export = 'export';
}

