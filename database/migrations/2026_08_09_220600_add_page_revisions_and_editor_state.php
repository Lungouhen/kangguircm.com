<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->unsignedInteger('lock_version')->default(1)->after('content');
            $table->softDeletes();
        });
        Schema::create('page_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('content');
            $table->json('settings');
            $table->string('reason')->default('manual');
            $table->timestamps();
            $table->index(['page_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_revisions');
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropColumn('lock_version');
        });
    }
};
