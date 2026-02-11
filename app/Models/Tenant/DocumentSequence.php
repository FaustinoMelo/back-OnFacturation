<?php

namespace App\Models\Tenant;

use App\Enums\DocumentType;
use App\Models\System\Company;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSequence extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'company_id',
        'document_type',
        'series',
        'year',
        'current_number',
        'format',
        'is_active',
    ];

    protected $casts = [
        'document_type' => DocumentType::class,
        'year' => 'integer',
        'current_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

