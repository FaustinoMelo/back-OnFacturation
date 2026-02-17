<?php

namespace App\Modules\Tax\Models;

use Database\Factories\TaxExemptionReasonFactory;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxExemptionReason extends Model
{
    use HasFactory, BelongsToTenant;

    protected static function newFactory(): TaxExemptionReasonFactory
    {
        return TaxExemptionReasonFactory::new();
    }

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
