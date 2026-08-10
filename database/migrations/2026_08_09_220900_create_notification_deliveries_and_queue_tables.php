<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_deliveries', function (Blueprint $table): void {
            $table->id();
            $table->string('event', 80);
            $table->string('notifiable_type', 120);
            $table->unsignedBigInteger('notifiable_id');
            $table->string('channel', 30);
            $table->string('provider', 40);
            $table->char('recipient_hash', 64);
            $table->string('recipient_masked', 40);
            $table->string('template', 120)->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('provider_message_id', 180)->nullable()->unique();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->string('failure_code', 80)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->unique(['event','notifiable_type','notifiable_id','channel','recipient_hash'],'notification_delivery_idempotency');
            $table->index(['status','created_at']);
        });

        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table): void {
                $table->id(); $table->string('queue')->index(); $table->longText('payload');
                $table->unsignedTinyInteger('attempts'); $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at'); $table->unsignedInteger('created_at');
            });
        }
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table): void {
                $table->id(); $table->string('uuid')->unique(); $table->text('connection'); $table->text('queue');
                $table->longText('payload'); $table->longText('exception'); $table->timestamp('failed_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
    }
};
