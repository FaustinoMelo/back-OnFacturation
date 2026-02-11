<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name');
            $table->string('sku', 100)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->decimal('min_price', 15, 2)->nullable();
            $table->string('unit', 20)->default('un');
            $table->enum('type', ['product', 'service'])->default('product');
            $table->boolean('track_stock')->default(false);
            $table->boolean('has_variants')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'is_active'], 'idx_company_tenant');
            $table->index(['company_id', 'sku'], 'idx_sku');
            $table->index(['company_id', 'name'], 'idx_product_search');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

