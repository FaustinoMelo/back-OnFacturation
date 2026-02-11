<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('rate', 8, 4);
            $table->enum('tax_type', ['iva', 'iss', 'icms', 'ipi', 'other'])->default('iva');
            $table->foreignId('exemption_reason_id')->nullable()->constrained('tax_exemption_reasons')->onDelete('set null');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'is_active'], 'idx_company_active');
            $table->index(['company_id', 'rate'], 'idx_rate_search');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};

