<?php

use App\Models\BlogCategory;
use App\Models\BlogTag;
use Database\Seeders\BlogTaxonomySeeder;

test('blog taxonomy seeder creates categories and links tags', function () {
    $this->seed(BlogTaxonomySeeder::class);

    $devops = BlogCategory::query()->where('slug', 'devops')->firstOrFail();

    $devopsTagSlugs = $devops->tags()->pluck('slug')->all();

    expect($devopsTagSlugs)
        ->toContain('docker')
        ->toContain('ci')
        ->toContain('deployment');

    $devopsOnly = BlogTag::query()
        ->whereHas('categories', fn ($query) => $query->whereKey($devops->getKey()))
        ->pluck('slug')
        ->all();

    expect($devopsOnly)->toContain('docker');
});
