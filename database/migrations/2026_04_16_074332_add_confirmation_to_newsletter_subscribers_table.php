<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table): void {
            $table->string('confirmation_token', 64)->nullable()->unique()->after('unsubscribe_token');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_token');
        });

        DB::table('newsletter_subscribers')->update([
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table): void {
            $table->dropColumn(['confirmation_token', 'confirmed_at']);
        });
    }
};
