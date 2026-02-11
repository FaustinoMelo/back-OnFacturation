<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'operator', 'viewer'])->default('operator');
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('invited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'company_id'], 'unique_user_company');
            $table->index(['company_id', 'role', 'is_active'], 'idx_company_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
    }
};

