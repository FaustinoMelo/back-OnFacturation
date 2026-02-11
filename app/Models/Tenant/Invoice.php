<?php

namespace App\Models\Tenant;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'sequence_id',
        'number',
        'full_number',
        'status',
        'type',
        'date',
        'due_date',
        'payment_term_id',
        'subtotal',
        'tax_total',
        'discount_total',
        'total',
        'paid_amount',
        'currency',
        'exchange_rate',
        'notes',
        'internal_notes',
        'hash',
        'qr_code',
        'atcud',
        'sent_to_tax_authority',
        'tax_authority_sent_at',
        'created_by',
        'issued_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'type' => InvoiceType::class,
        'date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'sent_to_tax_authority' => 'boolean',
        'tax_authority_sent_at' => 'datetime',
        'issued_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(DocumentSequence::class, 'sequence_id');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

