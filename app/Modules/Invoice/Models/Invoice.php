<?php

namespace App\Modules\Invoice\Models;

use Database\Factories\InvoiceFactory;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }

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

    protected function casts(): array
    {
        return [
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
    }

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
        return $this->belongsTo(\App\Models\Tenant\DocumentSequence::class, 'sequence_id');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant\PaymentTerm::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\Payment::class);
    }
}
