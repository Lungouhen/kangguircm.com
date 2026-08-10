<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table): void {
            $table->id(); $table->string('name'); $table->string('slug')->unique();
            $table->string('purpose')->default('general'); $table->boolean('is_active')->default(true);
            $table->text('description')->nullable(); $table->string('submit_label')->default('Submit');
            $table->text('success_message')->nullable(); $table->boolean('consent_required')->default(true);
            $table->text('consent_text')->nullable(); $table->string('policy_version',40)->nullable();
            $table->boolean('create_lead')->default(false); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('form_fields', function (Blueprint $table): void {
            $table->id(); $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->string('name',80); $table->string('label'); $table->string('type',30);
            $table->string('placeholder')->nullable(); $table->text('help_text')->nullable(); $table->json('options')->nullable();
            $table->boolean('is_required')->default(false); $table->unsignedSmallInteger('min_length')->nullable();
            $table->unsignedSmallInteger('max_length')->nullable(); $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedTinyInteger('width')->default(12); $table->timestamps();
            $table->unique(['form_id','name']); $table->index(['form_id','sort_order']);
        });
        Schema::create('form_submissions', function (Blueprint $table): void {
            $table->id(); $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->longText('payload'); $table->string('status',30)->default('new');
            $table->string('source',120)->nullable(); $table->string('landing_page',500)->nullable();
            $table->char('visitor_hash',64)->nullable(); $table->text('consent_text')->nullable();
            $table->string('policy_version',40)->nullable(); $table->timestamp('consented_at')->nullable();
            $table->timestamps(); $table->index(['form_id','status','created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('form_submissions'); Schema::dropIfExists('form_fields'); Schema::dropIfExists('forms'); }
};
