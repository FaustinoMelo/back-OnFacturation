<?php

namespace App\Models\System;

use App\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'currency',
        'locale',
        'timezone',
        'logo_path',
        'settings',
        'subscription_plan',
        'max_users',
        'is_active',
        'trial_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'subscription_plan' => SubscriptionPlan::class,
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'max_users' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\User\Models\User::class, 'company_user')
            ->withPivot(['role', 'is_active', 'invited_by', 'joined_at'])
            ->withTimestamps();
    }

    public function customers(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\Product::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\Invoice::class);
    }
}

