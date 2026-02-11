<?php

namespace App\Services\Tenant;

class TenantScope
{
    private static ?int $companyId = null;

    public static function setCompanyId(int $companyId): void
    {
        self::$companyId = $companyId;
    }

    public static function getCompanyId(): ?int
    {
        return self::$companyId;
    }

    public static function clear(): void
    {
        self::$companyId = null;
    }
}

