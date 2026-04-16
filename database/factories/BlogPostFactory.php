<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_category_id' => BlogCategory::factory(),
            'title' => fake()->sentence(6),
            'slug' => '',
            'excerpt' => fake()->paragraph(),
            'body_markdown' => implode("\n\n", [
                fake()->paragraph(),
                fake()->paragraph(),
                "```php\n<?php\n\necho 'hello';\n```",
                fake()->paragraph(),
            ]),
            'featured_image_path' => null,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'published_at' => now()->subDay(),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (): array => [
            'published_at' => now()->addDay(),
        ]);
    }
}
