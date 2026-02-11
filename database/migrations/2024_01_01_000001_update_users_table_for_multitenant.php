<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedBigInteger('active_company_id')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->index('active_company_id', 'idx_active_company');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('idx_active_company');
            $table->dropColumn(['active_company_id', 'last_login_at']);
        });
    }
};

