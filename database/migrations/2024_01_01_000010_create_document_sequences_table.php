<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_sequences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->enum('document_type', ['invoice', 'credit_note', 'debit_note', 'receipt', 'estimate']);
            $table->string('series', 20);
            $table->unsignedInteger('year');
            $table->unsignedInteger('current_number')->default(1);
            $table->string('format', 50)->default('{series}/{year}/{number}');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'document_type', 'series', 'year'], 'unique_sequence');
            $table->index(['company_id', 'document_type', 'is_active'], 'idx_company_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
    }
};

