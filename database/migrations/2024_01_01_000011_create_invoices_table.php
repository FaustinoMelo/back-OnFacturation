<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('sequence_id')->constrained('document_sequences')->onDelete('restrict');
            $table->unsignedInteger('number');
            $table->string('full_number', 100);
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'cancelled', 'archived'])->default('draft');
            $table->enum('type', ['normal', 'simplified', 'export'])->default('normal');
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->onDelete('set null');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax_total', 15, 2)->default(0.00);
            $table->decimal('discount_total', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('outstanding_amount', 15, 2)->storedAs('total - paid_amount');
            $table->string('currency', 3)->default('EUR');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('hash', 255)->nullable();
            $table->string('qr_code', 255)->nullable();
            $table->string('atcud', 50)->nullable();
            $table->boolean('sent_to_tax_authority')->default(false);
            $table->timestamp('tax_authority_sent_at')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'full_number'], 'unique_full_number');
            $table->index(['company_id', 'status'], 'idx_company_status');
            $table->index(['company_id', 'customer_id'], 'idx_customer_invoices');
            $table->index(['company_id', 'date'], 'idx_date_range');
            $table->index(['company_id', 'outstanding_amount'], 'idx_outstanding');
            $table->index(['company_id', 'sent_to_tax_authority'], 'idx_tax_authority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

