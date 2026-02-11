<?php

namespace App\Models\Tenant;

use App\Enums\TaxType;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tax extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'company_id',
        'name',
        'rate',
        'tax_type',
        'exemption_reason_id',
        'is_default',
        'is_active',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'tax_type' => TaxType::class,
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function exemptionReason(): BelongsTo
    {
        return $this->belongsTo(TaxExemptionReason::class, 'exemption_reason_id');
    }
}

