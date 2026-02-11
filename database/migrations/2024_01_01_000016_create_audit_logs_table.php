<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('event', 50);
            $table->string('auditable_type', 255);
            $table->unsignedBigInteger('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');

            $table->index(['company_id', 'event'], 'idx_company_event');
            $table->index(['auditable_type', 'auditable_id'], 'idx_auditable');
            $table->index(['user_id', 'created_at'], 'idx_user_date');
            $table->index('created_at', 'idx_date_range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

