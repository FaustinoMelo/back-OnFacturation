<?php

namespace App\Enums;

enum TaxType: string
{
    case Iva = 'iva';
    case Iss = 'iss';
    case Icms = 'icms';
    case Ipi = 'ipi';
    case Other = 'other';
}

