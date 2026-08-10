<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('meta_title', 60)->nullable()->after('featured_image');
            $table->string('meta_description', 160)->nullable()->after('meta_title');
            $table->string('canonical_url', 500)->nullable()->after('meta_description');
            $table->boolean('noindex')->default(false)->after('canonical_url');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn(['meta_title', 'meta_description', 'canonical_url', 'noindex']);
        });
    }
};
