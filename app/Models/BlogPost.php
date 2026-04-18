<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable([
    'blog_category_id',
    'title',
    'slug',
    'excerpt',
    'body_markdown',
    'body_html',
    'featured_image_path',
    'featured_blog_image_id',
    'is_featured',
    'is_headline',
    'published_at',
    'reading_time_minutes',
    'meta_title',
    'meta_description',
    'meta_noindex',
    'meta_og_blog_image_id',
])]
class BlogPost extends Model
{
    use HasFactory;

    public const PUBLIC_PER_PAGE = 10;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_headline' => 'boolean',
            'meta_noindex' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $post): void {
            if ($post->slug === null || $post->slug === '') {
                $post->slug = self::generateUniqueSlug($post->title, $post->getKey());
            }

            $post->reading_time_minutes = self::estimateReadingTimeMinutes($post->body_markdown);

            // Cached HTML for fast rendering on the public site.
            $post->body_html = Str::markdown($post->body_markdown);

            if ($post->is_headline === true && $post->isDirty('is_headline')) {
                self::query()
                    ->whereKeyNot($post->getKey() ?: 0)
                    ->where('is_headline', true)
                    ->update(['is_headline' => false]);
            }
        });
    }

    /**
     * @return BelongsTo<BlogCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * @return BelongsToMany<BlogTag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class);
    }

    /**
     * @return BelongsTo<BlogImage, $this>
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(BlogImage::class, 'featured_blog_image_id');
    }

    /**
     * @return BelongsTo<BlogImage, $this>
     */
    public function metaOgImage(): BelongsTo
    {
        return $this->belongsTo(BlogImage::class, 'meta_og_blog_image_id');
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    private static function estimateReadingTimeMinutes(string $markdown): int
    {
        $wordCount = str_word_count(strip_tags($markdown));

        return max(1, (int) ceil($wordCount / 200));
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (self::query()
            ->when($ignoreId !== null, fn (Builder $query): Builder => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
