<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogTaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web' => [
                'accent' => 'primary',
                'tags' => [
                    'inertia',
                    'vue',
                    'laravel',
                    'tailwind',
                    'vite',
                    'typescript',
                    'javascript',
                    'performance',
                    'accessibility',
                    'ui',
                    'ux',
                ],
            ],
            'Backend' => [
                'accent' => 'amber',
                'tags' => [
                    'api',
                    'rest',
                    'auth',
                    'authorization',
                    'validation',
                    'queues',
                    'jobs',
                    'caching',
                    'rate-limiting',
                    'database',
                    'mysql',
                    'migrations',
                    'eloquent',
                    'php',
                ],
            ],
            'Frontend' => [
                'accent' => 'secondary',
                'tags' => [
                    'ui',
                    'ux',
                    'accessibility',
                    'performance',
                    'animations',
                    'design-systems',
                    'components',
                    'tailwind',
                    'vue',
                    'typescript',
                ],
            ],
            'DevOps' => [
                'accent' => 'slate',
                'tags' => [
                    'docker',
                    'sail',
                    'ci',
                    'testing',
                    'pest',
                    'linting',
                    'eslint',
                    'prettier',
                    'deployment',
                    'monitoring',
                ],
            ],
            'Architecture' => [
                'accent' => 'sky',
                'tags' => [
                    'architecture',
                    'refactoring',
                    'best-practices',
                    'patterns',
                    'monolith',
                    'microservices',
                    'ddd',
                    'clean-code',
                ],
            ],
        ];

        foreach ($categories as $name => $data) {
            $category = BlogCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'accent' => $data['accent']],
            );

            $tagIds = collect($data['tags'])
                ->unique()
                ->values()
                ->map(function (string $tagName): int {
                    $tag = BlogTag::query()->updateOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName],
                    );

                    return $tag->getKey();
                });

            $category->tags()->syncWithoutDetaching($tagIds->all());
        }
    }
}
