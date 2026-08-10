<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_policies', function (Blueprint $table): void {
            $table->id(); $table->string('type',50); $table->string('title'); $table->string('slug')->unique();
            $table->string('version',40); $table->date('effective_at'); $table->longText('content');
            $table->boolean('is_published')->default(false); $table->boolean('show_in_footer')->default(true);
            $table->timestamps(); $table->softDeletes(); $table->index(['type','is_published']);
        });
        Schema::create('visitor_consents', function (Blueprint $table): void {
            $table->id(); $table->char('visitor_hash',64); $table->boolean('necessary')->default(true);
            $table->boolean('analytics')->default(false); $table->boolean('marketing')->default(false);
            $table->boolean('preferences')->default(false); $table->string('policy_version',40)->nullable();
            $table->string('action',20); $table->timestamp('consented_at'); $table->timestamps();
            $table->index(['visitor_hash','consented_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('visitor_consents'); Schema::dropIfExists('legal_policies'); }
};
