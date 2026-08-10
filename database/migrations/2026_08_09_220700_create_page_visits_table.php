<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table): void {
            $table->id();
            $table->date('visited_on');
            $table->string('path', 500);
            $table->string('route_name', 120)->nullable();
            $table->string('content_type', 40)->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->char('visitor_hash', 64);
            $table->string('referrer_host', 255)->nullable();
            $table->string('utm_source', 120)->nullable();
            $table->string('utm_medium', 120)->nullable();
            $table->string('utm_campaign', 180)->nullable();
            $table->string('device_type', 20)->default('desktop');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['visited_on', 'path']);
            $table->index(['content_type', 'content_id']);
            $table->index(['visited_on', 'visitor_hash']);
            $table->index(['utm_source', 'visited_on']);
        });
    }

    public function down(): void { Schema::dropIfExists('page_visits'); }
};
