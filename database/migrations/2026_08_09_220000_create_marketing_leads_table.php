<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->string('specialty')->nullable();
            $table->unsignedSmallInteger('provider_count')->nullable();
            $table->string('billing_model')->nullable();
            $table->string('monthly_claims')->nullable();
            $table->string('primary_challenge')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_contact_time')->nullable();
            $table->string('source')->default('website');
            $table->string('landing_page')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('status')->default('new');
            $table->boolean('consent')->default(false);
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_leads');
    }
};
