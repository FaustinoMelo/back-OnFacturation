<?php

namespace App\Models\Tenant;

use App\Enums\CustomerType;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'customer_type',
        'payment_term_id',
        'price_list_id',
        'credit_limit',
        'current_balance',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'customer_type' => CustomerType::class,
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

