<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_entries', function (Blueprint $table): void {
            $table->id(); $table->string('type',40)->index(); $table->string('title'); $table->string('slug')->unique();
            $table->text('summary')->nullable(); $table->longText('body')->nullable(); $table->string('image',500)->nullable();
            $table->json('data')->nullable(); $table->string('meta_title',60)->nullable(); $table->string('meta_description',160)->nullable();
            $table->string('status',20)->default('draft'); $table->timestamp('published_at')->nullable(); $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps(); $table->softDeletes(); $table->index(['type','status','published_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('content_entries'); }
};
