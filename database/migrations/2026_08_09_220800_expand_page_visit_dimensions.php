<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->char('country_code', 2)->nullable()->after('device_type');
            $table->string('region', 120)->nullable()->after('country_code');
            $table->string('city', 120)->nullable()->after('region');
            $table->string('organization', 180)->nullable()->after('city');
            $table->string('browser', 40)->default('Other')->after('organization');
            $table->string('operating_system', 40)->default('Other')->after('browser');
            $table->string('reach_type', 30)->default('direct')->after('operating_system');
            $table->string('language', 12)->nullable()->after('reach_type');
            $table->index(['country_code', 'visited_on']);
            $table->index(['reach_type', 'visited_on']);
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->dropIndex(['country_code', 'visited_on']);
            $table->dropIndex(['reach_type', 'visited_on']);
            $table->dropColumn(['country_code','region','city','organization','browser','operating_system','reach_type','language']);
        });
    }
};
