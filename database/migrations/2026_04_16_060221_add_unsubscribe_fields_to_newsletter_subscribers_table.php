<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table): void {
            $table->string('unsubscribe_token', 64)->nullable()->unique();
            $table->timestamp('unsubscribed_at')->nullable();
        });

        foreach (DB::table('newsletter_subscribers')->cursor() as $row) {
            DB::table('newsletter_subscribers')->where('id', $row->id)->update([
                'unsubscribe_token' => Str::random(48),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table): void {
            $table->dropColumn(['unsubscribe_token', 'unsubscribed_at']);
        });
    }
};
