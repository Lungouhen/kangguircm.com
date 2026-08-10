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
            $table->string('meta_title')->nullable()->after('template');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('canonical_url')->nullable()->after('meta_description');
            $table->string('social_image')->nullable()->after('canonical_url');
            $table->string('schema_type')->default('WebPage')->after('social_image');
            $table->boolean('noindex')->default(false)->after('schema_type');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['meta_title', 'meta_description', 'canonical_url', 'social_image', 'schema_type', 'noindex']);
        });
    }
};
