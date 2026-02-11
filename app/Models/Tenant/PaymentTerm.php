<?php

namespace App\Models\Tenant;

use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTerm extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'company_id',
        'name',
        'days',
        'description',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'days' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

