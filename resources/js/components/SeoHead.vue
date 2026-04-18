<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, h } from 'vue';

export interface SeoArticleMeta {
    publishedTime?: string | null;
    modifiedTime?: string | null;
    author?: string | null;
    section?: string | null;
    tags?: string[];
}

export interface SeoPayload {
    title?: string | null;
    description?: string | null;
    canonical?: string | null;
    image?: string | null;
    imageAlt?: string | null;
    type?: string | null;
    siteName?: string | null;
    siteUrl?: string | null;
    locale?: string | null;
    twitterHandle?: string | null;
    twitterCard?: string | null;
    noindex?: boolean | null;
    article?: SeoArticleMeta | null;
    jsonLd?: Record<string, unknown> | Array<Record<string, unknown>> | null;
}

interface Props {
    seo?: Partial<SeoPayload> | null;
    title?: string;
}

const props = defineProps<Props>();

const page = usePage<{ seoDefaults: SeoPayload }>();

const merged = computed<Required<Omit<SeoPayload, 'article' | 'jsonLd' | 'noindex'>> & Pick<SeoPayload, 'article' | 'jsonLd' | 'noindex'>>(() => {
    const defaults = page.props.seoDefaults ?? ({} as SeoPayload);
    const overrides = (props.seo ?? {}) as SeoPayload;

    const siteUrl = (overrides.siteUrl ?? defaults.siteUrl ?? '').replace(/\/$/, '');
    const currentPath = page.url || '/';
    const computedCanonical = overrides.canonical
        ?? (siteUrl ? `${siteUrl}${currentPath.startsWith('/') ? currentPath : `/${currentPath}`}` : null);

    const baseTitle = props.title ?? overrides.title ?? defaults.title ?? '';

    return {
        title: baseTitle ?? '',
        description: overrides.description ?? defaults.description ?? '',
        canonical: computedCanonical ?? '',
        image: overrides.image ?? defaults.image ?? '',
        imageAlt: overrides.imageAlt ?? defaults.imageAlt ?? baseTitle ?? '',
        type: overrides.type ?? defaults.type ?? 'website',
        siteName: overrides.siteName ?? defaults.siteName ?? '',
        siteUrl,
        locale: overrides.locale ?? defaults.locale ?? 'en_US',
        twitterHandle: overrides.twitterHandle ?? defaults.twitterHandle ?? '',
        twitterCard: overrides.twitterCard ?? defaults.twitterCard ?? 'summary_large_image',
        noindex: overrides.noindex ?? defaults.noindex ?? false,
        article: overrides.article ?? defaults.article ?? null,
        jsonLd: overrides.jsonLd ?? defaults.jsonLd ?? null,
    };
});

const jsonLdString = computed<string | null>(() => {
    const jsonLd = merged.value.jsonLd;

    if (jsonLd === null || jsonLd === undefined) {
        return null;
    }

    return JSON.stringify(jsonLd);
});

const JsonLdScript = () =>
    jsonLdString.value
        ? h('script', {
              type: 'application/ld+json',
              innerHTML: jsonLdString.value,
          })
        : null;
</script>

<template>
    <Head :title="merged.title">
        <meta
            v-if="merged.description"
            name="description"
            :content="merged.description"
        />
        <meta
            v-if="merged.noindex"
            name="robots"
            content="noindex, nofollow"
        />
        <link
            v-if="merged.canonical"
            rel="canonical"
            :href="merged.canonical"
        />

        <meta property="og:type" :content="merged.type" />
        <meta property="og:title" :content="merged.title" />
        <meta
            v-if="merged.description"
            property="og:description"
            :content="merged.description"
        />
        <meta
            v-if="merged.canonical"
            property="og:url"
            :content="merged.canonical"
        />
        <meta
            v-if="merged.siteName"
            property="og:site_name"
            :content="merged.siteName"
        />
        <meta
            v-if="merged.locale"
            property="og:locale"
            :content="merged.locale"
        />
        <meta
            v-if="merged.image"
            property="og:image"
            :content="merged.image"
        />
        <meta
            v-if="merged.image && merged.imageAlt"
            property="og:image:alt"
            :content="merged.imageAlt"
        />

        <template v-if="merged.type === 'article' && merged.article">
            <meta
                v-if="merged.article.publishedTime"
                property="article:published_time"
                :content="merged.article.publishedTime"
            />
            <meta
                v-if="merged.article.modifiedTime"
                property="article:modified_time"
                :content="merged.article.modifiedTime"
            />
            <meta
                v-if="merged.article.section"
                property="article:section"
                :content="merged.article.section"
            />
            <meta
                v-if="merged.article.author"
                property="article:author"
                :content="merged.article.author"
            />
            <template v-if="merged.article.tags">
                <meta
                    v-for="tag in merged.article.tags"
                    :key="tag"
                    property="article:tag"
                    :content="tag"
                />
            </template>
        </template>

        <meta name="twitter:card" :content="merged.twitterCard" />
        <meta
            v-if="merged.twitterHandle"
            name="twitter:site"
            :content="merged.twitterHandle"
        />
        <meta name="twitter:title" :content="merged.title" />
        <meta
            v-if="merged.description"
            name="twitter:description"
            :content="merged.description"
        />
        <meta
            v-if="merged.image"
            name="twitter:image"
            :content="merged.image"
        />
        <meta
            v-if="merged.image && merged.imageAlt"
            name="twitter:image:alt"
            :content="merged.imageAlt"
        />
    </Head>

    <JsonLdScript />
</template>
