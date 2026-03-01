<?php

namespace App\Modules\User\Models;

use App\Models\System\Company;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function ($model): void {
            if (!$model->id) {
                $model->id = Str::uuid()->toString();
            }
        });
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'active_company_id' => 'integer',
    ];

    // Relações
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    public function activeCompany()
    {
        return $this->belongsTo(Company::class, 'active_company_id');
    }

    // --------------------------
    // Métodos obrigatórios JWT
    // --------------------------
    
    /**
     * Retorna o identificador que será armazenado no token
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna claims personalizadas
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}