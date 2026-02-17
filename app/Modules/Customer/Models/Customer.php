<?php

namespace App\Modules\Customer\Models;

use Database\Factories\CustomerFactory;
use App\Enums\CustomerType;
use App\Models\System\Company;
use App\Models\Tenant\PaymentTerm;
use App\Models\Traits\BelongsToTenant;
use App\Modules\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

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

    protected function casts(): array
    {
        return [
            'customer_type' => CustomerType::class,
            'credit_limit' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

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
