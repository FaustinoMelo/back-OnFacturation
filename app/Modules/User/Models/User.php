<?php

namespace App\Modules\User\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class User extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'active_company_id',
        'last_login_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'active_company_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model): void {
            if (!$model->id) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\System\Company::class, 'company_user')
            ->withPivot(['role', 'is_active', 'invited_by', 'joined_at'])
            ->withTimestamps();
    }

    public function activeCompany()
    {
        return $this->belongsTo(\App\Models\System\Company::class, 'active_company_id');
    }
}
            