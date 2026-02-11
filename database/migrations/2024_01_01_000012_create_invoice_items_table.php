<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->text('description');
            $table->decimal('quantity', 15, 4);
            $table->string('unit', 20);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('tax_rate', 8, 4)->default(0.0000);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->timestamps();

            $table->index('invoice_id', 'idx_invoice_items');
            $table->index(['product_id', 'product_variant_id'], 'idx_product_usage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};

