<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_leads', function (Blueprint $table): void {
            $table->dropIndex(['email']);
            $table->text('email')->change();
            $table->text('phone')->nullable()->change();
            $table->text('preferred_contact_time')->nullable()->change();
        });

        DB::table('marketing_leads')->orderBy('id')->chunkById(100, function ($leads): void {
            foreach ($leads as $lead) {
                $updates = [];
                foreach (['email', 'phone', 'message', 'preferred_contact_time'] as $field) {
                    if ($lead->{$field} !== null && !$this->isEncrypted((string) $lead->{$field})) {
                        $updates[$field] = Crypt::encryptString((string) $lead->{$field});
                    }
                }
                if ($updates) {
                    DB::table('marketing_leads')->where('id', $lead->id)->update($updates);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('marketing_leads')->orderBy('id')->chunkById(100, function ($leads): void {
            foreach ($leads as $lead) {
                $updates = [];
                foreach (['email', 'phone', 'message', 'preferred_contact_time'] as $field) {
                    if ($lead->{$field} !== null && $this->isEncrypted((string) $lead->{$field})) {
                        $updates[$field] = Crypt::decryptString((string) $lead->{$field});
                    }
                }
                if ($updates) {
                    DB::table('marketing_leads')->where('id', $lead->id)->update($updates);
                }
            }
        });

        Schema::table('marketing_leads', function (Blueprint $table): void {
            $table->string('email')->change();
            $table->string('phone')->nullable()->change();
            $table->string('preferred_contact_time')->nullable()->change();
            $table->index('email');
        });
    }

    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (Throwable) {
            return false;
        }
    }
};
