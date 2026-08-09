<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable(); // Group logs (e.g., 'user_actions', 'cms_changes')
            $table->string('description'); // Human readable description
            $table->nullableMorphs('subject', 'subject'); // The model being audited (e.g., User, Page)
            $table->nullableMorphs('causer', 'causer'); // Who did it (usually a User)
            $table->json('properties')->nullable(); // Additional metadata
            $table->json('old_values')->nullable(); // Snapshot before change
            $table->json('new_values')->nullable(); // Snapshot after change
            $table->string('event')->default('created'); // created, updated, deleted
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('log_name');
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
