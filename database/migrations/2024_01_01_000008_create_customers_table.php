<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('tax_id', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 2)->default('PT');
            $table->enum('customer_type', ['individual', 'company'])->default('individual');
            $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->onDelete('set null');
            $table->unsignedBigInteger('price_list_id')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0.00);
            $table->decimal('current_balance', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'is_active'], 'idx_company_tenant');
            $table->index(['company_id', 'name', 'tax_id'], 'idx_customer_search');
            $table->index(['company_id', 'current_balance'], 'idx_balance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

