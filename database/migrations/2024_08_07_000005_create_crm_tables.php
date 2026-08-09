<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->integer('employees_count')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
            $table->index('industry');
        });

        // Contacts table
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('title')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('email');
            $table->index('company_id');
        });

        // Pipelines table
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Pipeline Stages table
        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->string('color')->nullable();
            $table->integer('probability')->default(0);
            $table->timestamps();
            $table->index('pipeline_id');
            $table->index('order');
        });

        // Deals table
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->foreignId('pipeline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('pipeline_stages')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->date('expected_close_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->string('status')->default('open');
            $table->integer('probability')->default(0);
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('pipeline_id');
            $table->index('stage_id');
            $table->index('status');
            $table->index('owner_id');
        });

        // Activities table
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained()->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index('type');
            $table->index('status');
            $table->index('scheduled_at');
        });

        // Activity Participants pivot table
        Schema::create('activity_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('required')->default(true);
            $table->boolean('attended')->default(false);
            $table->timestamps();
            $table->unique(['activity_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_participants');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('pipeline_stages');
        Schema::dropIfExists('pipelines');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('companies');
    }
};
