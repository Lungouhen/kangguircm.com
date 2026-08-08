<?php

declare(strict_types=1);

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
        Schema::create('subscriber_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('subscriber_count')->default(0);
            $table->tinyInt(1)->default(1)->comment('1=active, 0=archived');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->index('is_active');
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->json('custom_fields')->nullable(); // Dynamic attributes
            $table->string('source')->default('manual'); // manual, import, api
            $table->tinyInt(1)->default(1)->comment('1=subscribed, 0=unsubscribed, 2=bounced');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('unsubscribe_token')->unique()->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->index(['email', 'status']);
            $table->index('status');
            $table->index('created_at');
        });

        Schema::create('list_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_list_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unique(['subscriber_list_id', 'subscriber_id']);
            $table->index('subscriber_list_id');
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->longText('html_content');
            $table->text('text_content')->nullable();
            $table->string('category')->default('general'); // newsletter, transactional, promo
            $table->tinyInt(1)->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->index('category');
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('email_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('subject');
            $table->longText('content');
            $table->text('text_content')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('open_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->unsignedInteger('bounce_count')->default(0);
            $table->unsignedInteger('unsubscribe_count')->default(0);
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->index(['status', 'scheduled_at']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('campaign_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_list_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unique(['campaign_id', 'subscriber_list_id']);
        });

        Schema::create('email_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->nullable(); // SMTP message ID
            $table->tinyInt(1)->default(0)->comment('1=delivered');
            $table->timestamp('delivered_at')->nullable();
            $table->tinyInt(1)->default(0)->comment('1=opened');
            $table->timestamp('opened_at')->nullable();
            $table->unsignedInteger('open_count')->default(0);
            $table->tinyInt(1)->default(0)->comment('1=clicked');
            $table->timestamp('clicked_at')->nullable();
            $table->unsignedInteger('click_count')->default(0);
            $table->tinyInt(1)->default(0)->comment('1=bounced');
            $table->enum('bounce_type', ['hard', 'soft'])->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unique(['campaign_id', 'subscriber_id']);
            $table->index(['campaign_id', 'opened_at']);
            $table->index(['campaign_id', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_tracking');
        Schema::dropIfExists('campaign_list');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('list_subscriber');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('subscriber_lists');
    }
};
