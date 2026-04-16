<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table): void {
            $table->string('meta_title', 180)->nullable()->after('excerpt');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
            $table->boolean('meta_noindex')->default(false)->after('meta_description');
            $table
                ->foreignId('meta_og_blog_image_id')
                ->nullable()
                ->after('featured_blog_image_id')
                ->constrained('blog_images')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('meta_og_blog_image_id');
            $table->dropColumn(['meta_title', 'meta_description', 'meta_noindex']);
        });
    }
};
