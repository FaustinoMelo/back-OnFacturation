<?php

namespace App\Modules\Product\Models;

use App\Enums\ProductType;
use App\Models\System\Company;
use App\Models\Tenant\Category;
use App\Modules\Tax\Models\Tax;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'tax_id',
        'category_id',
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'min_price',
        'unit',
        'type',
        'track_stock',
        'has_variants',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'min_price' => 'decimal:2',
            'type' => ProductType::class,
            'track_stock' => 'boolean',
            'has_variants' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
