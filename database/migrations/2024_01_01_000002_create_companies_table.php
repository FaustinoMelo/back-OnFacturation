<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('tax_id', 50)->unique();
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 2)->default('PT');
            $table->string('currency', 3)->default('EUR');
            $table->string('locale', 10)->default('pt-PT');
            $table->string('timezone', 50)->default('Europe/Lisbon');
            $table->string('logo_path', 255)->nullable();
            $table->json('settings')->nullable();
            $table->enum('subscription_plan', ['free', 'basic', 'premium', 'enterprise'])->default('free');
            $table->unsignedInteger('max_users')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();

            $table->index('tax_id', 'idx_tax_id');
            $table->index(['subscription_plan', 'is_active'], 'idx_subscription');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

