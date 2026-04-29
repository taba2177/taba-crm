<?php
// src/Http/Middleware/InjectSeoForBots.php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class InjectSeoForBots
{
    private const BOT_AGENTS = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot',
        'facebookexternalhit', 'twitterbot', 'linkedinbot',
        'whatsapp', 'telegram', 'slackbot', 'discordbot',
        'applebot', 'pinterest',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->isBot($request)) {
            return $response;
        }

        $indexPath = public_path('index.html');
        if (! file_exists($indexPath)) {
            return $response;
        }

        $html = file_get_contents($indexPath);
        $html = $this->patchLang($html);
        [$meta, $jsonLd] = $this->buildSeoTags($request);
        $html = str_replace('</head>', $meta . $jsonLd . '</head>', $html);

        return response($html, 200)->header('Content-Type', 'text/html; charset=utf-8');
    }

    // -------------------------------------------------------------------------

    private function isBot(Request $request): bool
    {
        $ua = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_AGENTS as $bot) {
            if (str_contains($ua, $bot)) return true;
        }
        return false;
    }

    private function patchLang(string $html): string
    {
        return preg_replace('/<html([^>]*)>/i', '<html$1 lang="' . app()->getLocale() . '">', $html, 1);
    }

    private function buildSeoTags(Request $request): array
    {
        $canonical = url($request->path());
        $base      = url('/');
        $segments  = array_values(array_filter(explode('/', $request->path())));
        $locale   = app()->getLocale();
        $settings = Cache::remember(
            'api_init_' . $locale,
            config('crm.api.cache_ttl', 300),
            fn () => CrmSetting::getAllGrouped()
        );

        // Locale-aware extractor: CrmSetting::getAllGrouped() returns raw JSON-cast values;
        // translatable settings are PHP arrays ['ar' => '...', 'en' => '...']
        $str = function ($value, string $default = '') use ($locale): string {
            if (is_array($value)) {
                return (string) ($value[$locale] ?? $value['en'] ?? $value['ar'] ?? $default);
            }
            return (string) ($value ?? $default);
        };

        $siteName = $str($settings['general']['site_name'] ?? null, config('app.name'));
        $ogImage  = $str($settings['general']['og_image'] ?? null, '');

        // --- Resolve page type ---
        if (count($segments) === 0) {
            // Home
            $title       = $siteName;
            $description = $str($settings['general']['site_description'] ?? null, '');
            $imageUrl    = $ogImage;
            $imageAlt    = $siteName;
            $imageCap    = '';
            $ogType      = 'website';
            $jsonLd      = $this->homeLd($siteName, $description, $base, $ogImage);
        } elseif (count($segments) === 1) {
            // Category
            $cat         = PostCategory::where('slug', $segments[0])->first();
            $title       = $cat?->name ?? $siteName;
            $description = $cat?->description ?? '';
            $imageUrl    = '';
            $imageAlt    = '';
            $imageCap    = '';
            $ogType      = 'website';
            $catUrl      = url($segments[0]);
            $jsonLd      = $this->categoryLd($title, $description, $catUrl, $siteName, $base);
        } elseif (count($segments) === 2) {
            // Post
            $post        = Post::where('slug', $segments[1])->published()->first();
            $title       = $post?->meta_title ?? $post?->title ?? $siteName;
            $description = $post?->meta_description ?? '';
            $firstImage  = $post?->images->first();
            $imageUrl    = $firstImage?->url ?? '';
            $imageAlt    = $firstImage?->alt ?? $title;
            $imageCap    = $firstImage?->caption ?? '';
            $ogType      = 'article';
            $catUrl      = url($segments[0]);
            $catName     = $post?->postCategory?->name ?? $segments[0];
            $jsonLd      = $this->articleLd(
                $title, $description, $canonical, $imageUrl, $imageAlt, $imageCap,
                $post?->created_at?->toIso8601String() ?? '',
                $post?->updated_at?->toIso8601String() ?? '',
                $siteName, $ogImage, $catName, $catUrl, $base
            );
        } else {
            // Unknown — no injection
            return ['', ''];
        }

        $meta = $this->buildMeta($title, $description, $canonical, $imageUrl, $imageAlt, $ogType);
        return [$meta, $jsonLd];
    }

    private function buildMeta(
        string $title, string $description, string $canonical,
        string $imageUrl, string $imageAlt, string $ogType
    ): string {
        $lines = [
            "<title>{$title}</title>",
            "<meta name=\"description\" content=\"" . e($description) . "\">",
            "<meta name=\"robots\" content=\"index, follow\">",
            "<meta property=\"og:title\" content=\"" . e($title) . "\">",
            "<meta property=\"og:description\" content=\"" . e($description) . "\">",
            "<meta property=\"og:url\" content=\"{$canonical}\">",
            "<meta property=\"og:type\" content=\"{$ogType}\">",
            "<meta name=\"twitter:card\" content=\"summary_large_image\">",
            "<meta name=\"twitter:title\" content=\"" . e($title) . "\">",
            "<meta name=\"twitter:description\" content=\"" . e($description) . "\">",
            "<link rel=\"canonical\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"ar\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"en\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$canonical}\">",
        ];

        if ($imageUrl) {
            $lines[] = "<link rel=\"preload\" as=\"image\" fetchpriority=\"high\" href=\"{$imageUrl}\">";
            $lines[] = "<meta property=\"og:image\" content=\"{$imageUrl}\">";
            $lines[] = "<meta property=\"og:image:alt\" content=\"" . e($imageAlt) . "\">";
            $lines[] = "<meta name=\"twitter:image\" content=\"{$imageUrl}\">";
            $lines[] = "<meta name=\"twitter:image:alt\" content=\"" . e($imageAlt) . "\">";
        }

        return implode("\n", $lines) . "\n";
    }

    private function homeLd(string $siteName, string $desc, string $base, string $logo): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'           => 'WebSite',
                    'name'            => $siteName,
                    'url'             => $base,
                    'description'     => $desc,
                    'potentialAction' => [
                        '@type'       => 'SearchAction',
                        'target'      => "{$base}/search?q={search_term_string}",
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
                [
                    '@type' => 'Organization',
                    'name'  => $siteName,
                    'url'   => $base,
                    'logo'  => $logo,
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    private function categoryLd(string $name, string $desc, string $catUrl, string $siteName, string $base): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                ['@type' => 'CollectionPage', 'name' => $name, 'description' => $desc, 'url' => $catUrl],
                [
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => $siteName, 'item' => $base],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $name,     'item' => $catUrl],
                    ],
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    private function articleLd(
        string $title, string $desc, string $canonical,
        string $imageUrl, string $imageAlt, string $imageCap,
        string $published, string $modified,
        string $siteName, string $logo, string $catName, string $catUrl, string $base
    ): string {
        $image = $imageUrl ? [
            '@type'       => 'ImageObject',
            'url'         => $imageUrl,
            'description' => $imageAlt,
            'caption'     => $imageCap,
        ] : null;

        $article = [
            '@type'         => 'Article',
            'headline'      => $title,
            'description'   => $desc,
            'url'           => $canonical,
            'datePublished' => $published,
            'dateModified'  => $modified,
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $siteName,
                'logo'  => ['@type' => 'ImageObject', 'url' => $logo],
            ],
        ];
        if ($image) $article['image'] = $image;

        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                $article,
                [
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => $siteName, 'item' => $base],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $catName,  'item' => $catUrl],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $title,    'item' => $canonical],
                    ],
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
