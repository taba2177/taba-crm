<?php
// src/Http/Middleware/InjectSeoForBots.php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Page;
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

        $indexPath = $this->resolveSpaIndex();
        if ($indexPath === null) {
            return $response;
        }

        $html = Cache::remember('spa_index_html_' . md5($indexPath), config('crm.api.cache_ttl', 300), fn() => file_get_contents($indexPath));
        $html = $this->patchLang($html);
        [$meta, $jsonLd] = $this->buildSeoTags($request);
        // Remove the placeholder <title> so we don't end up with two title tags
        // (bots/WhatsApp use the first one, which would still be "Site" otherwise).
        $html = preg_replace('/<title>[^<]*<\/title>/i', '', $html, 1);
        $html = str_replace('</head>', $meta . $jsonLd . '</head>', $html);

        return response($html, 200)->header('Content-Type', 'text/html; charset=utf-8');
    }

    // -------------------------------------------------------------------------

    private function resolveSpaIndex(): ?string
    {
        foreach (['app.html', 'index.html'] as $name) {
            $path = public_path($name);
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    private function isBot(Request $request): bool
    {
        $ua = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_AGENTS as $bot) {
            if (str_contains($ua, $bot)) return true;
        }
        return false;
    }

    /**
     * Ensure an image path is an absolute URL that external crawlers can fetch.
     * Relative paths (e.g. /storage/logo.png) are prefixed with APP_URL.
     */
    private function absoluteUrl(string $url): string
    {
        if ($url === '') return '';
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        return rtrim(config('app.url'), '/') . '/' . ltrim($url, '/');
    }

    private function patchLang(string $html): string
    {
        $locale = app()->getLocale();
        // Replace existing lang attribute if present, otherwise append it
        if (preg_match('/<html[^>]*\slang=/i', $html)) {
            return preg_replace('/(<html[^>]*)\slang="[^"]*"/i', '$1 lang="' . $locale . '"', $html, 1);
        }
        return preg_replace('/<html([^>]*)>/i', '<html$1 lang="' . $locale . '">', $html, 1);
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

        $siteName    = $str($settings['business']['crm_business_name'] ?? null, config('app.name'));
        $ogImage     = $this->absoluteUrl($str($settings['business']['crm_business_logo'] ?? null, ''));
        $seoTitle    = $str($settings['seo']['crm_seo_default_title'] ?? null, $siteName);
        $seoDesc     = $str($settings['seo']['crm_seo_default_description'] ?? null, '');

        // --- Resolve page type ---
        if (count($segments) === 0) {
            // Home
            $title       = $seoTitle;
            $description = $seoDesc;
            $imageUrl    = $ogImage;
            $imageAlt    = $siteName;
            $imageCap    = '';
            $imageWidth  = 0;
            $imageHeight = 0;
            $imageType   = '';
            $ogType      = 'website';
            $jsonLd      = $this->homeLd($siteName, $description, $base, $ogImage);
        } elseif (count($segments) === 1) {
            // Try PostCategory first, then Page, then fall back to site defaults
            $cat        = PostCategory::where('slug', $segments[0])->first();
            $page       = $cat ? null : Page::where('slug', $segments[0])->first();
            $title      = $cat?->name ?? $page?->title ?? $seoTitle;
            $description = $cat?->description ?? $seoDesc;
            $catImageRaw = $cat?->image ?? '';
            $catImageUrl = $catImageRaw
                ? $this->absoluteUrl(
                    str_starts_with((string) $catImageRaw, 'http') ? $catImageRaw : Storage::url($catImageRaw)
                )
                : '';
            $imageUrl    = $catImageUrl ?: $ogImage;
            $imageAlt    = $title;
            $imageWidth  = 0;
            $imageHeight = 0;
            $imageType   = '';
            $ogType      = 'website';
            $pageUrl     = url($segments[0]);
            $jsonLd      = $this->categoryLd($title, $description, $pageUrl, $siteName, $base);
        } elseif (count($segments) === 2) {
            // Post
            $post        = Post::where('slug', $segments[1])->published()->first();
            $title       = $post?->meta_title ?? $post?->title ?? $siteName;
            $description = $post?->meta_description ?? '';
            $firstImage  = $post?->images->first();
            $imageUrl    = $this->absoluteUrl($firstImage?->url ?? '');
            $imageAlt    = $firstImage?->alt ?? $title;
            $imageCap    = $firstImage?->caption ?? '';
            $imageWidth  = $firstImage?->width ?? 0;
            $imageHeight = $firstImage?->height ?? 0;
            $imageType   = $firstImage?->type ?? '';
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
            // Unknown depth — serve site defaults so bots still get og:image
            $title       = $seoTitle;
            $description = $seoDesc;
            $imageUrl    = $ogImage;
            $imageAlt    = $siteName;
            $imageWidth  = 0;
            $imageHeight = 0;
            $imageType   = '';
            $ogType      = 'website';
            $jsonLd      = '';
        }

        $meta = $this->buildMeta($title, $description, $canonical, $imageUrl, $imageAlt, $ogType, $imageWidth, $imageHeight, $imageType, $siteName);
        return [$meta, $jsonLd];
    }

    private function buildMeta(
        string $title, string $description, string $canonical,
        string $imageUrl, string $imageAlt, string $ogType,
        int $imageWidth = 0, int $imageHeight = 0, string $imageType = '',
        string $siteName = ''
    ): string {
        $locale        = app()->getLocale();
        $siteName      = $siteName ?: config('app.name');
        $safeCanonical = e($canonical);
        $lines = [
            "<title>" . e($title) . "</title>",
            "<meta name=\"description\" content=\"" . e($description) . "\">",
            "<meta name=\"robots\" content=\"index, follow\">",
            "<meta property=\"og:site_name\" content=\"" . e($siteName) . "\">",
            "<meta property=\"og:locale\" content=\"" . e($locale) . "\">",
            "<meta property=\"og:title\" content=\"" . e($title) . "\">",
            "<meta property=\"og:description\" content=\"" . e($description) . "\">",
            "<meta property=\"og:url\" content=\"{$safeCanonical}\">",
            "<meta property=\"og:type\" content=\"" . e($ogType) . "\">",
            "<meta name=\"twitter:card\" content=\"summary_large_image\">",
            "<meta name=\"twitter:title\" content=\"" . e($title) . "\">",
            "<meta name=\"twitter:description\" content=\"" . e($description) . "\">",
            "<link rel=\"canonical\" href=\"{$safeCanonical}\">",
        ];

        $locales = config('crm.available_locales', ['ar']);
        foreach ((array) $locales as $loc) {
            $lines[] = "<link rel=\"alternate\" hreflang=\"{$loc}\" href=\"{$safeCanonical}\">";
        }
        if (count((array) $locales) > 1) {
            $lines[] = "<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$safeCanonical}\">";
        }

        if ($imageUrl) {
            $safeImageUrl = e($imageUrl);
            $lines[] = "<meta property=\"og:image\" content=\"{$safeImageUrl}\">";
            $lines[] = "<meta property=\"og:image:alt\" content=\"" . e($imageAlt) . "\">";
            if ($imageWidth)  $lines[] = "<meta property=\"og:image:width\" content=\"{$imageWidth}\">";
            if ($imageHeight) $lines[] = "<meta property=\"og:image:height\" content=\"{$imageHeight}\">";
            if ($imageType)   $lines[] = "<meta property=\"og:image:type\" content=\"" . e($imageType) . "\">";
            $lines[] = "<meta name=\"twitter:image\" content=\"{$safeImageUrl}\">";
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
                array_filter([
                    '@type' => 'Organization',
                    'name'  => $siteName,
                    'url'   => $base,
                    'logo'  => $logo ? ['@type' => 'ImageObject', 'url' => $logo] : null,
                ]),
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
            '@type'            => 'Article',
            'headline'         => $title,
            'description'      => $desc,
            'url'              => $canonical,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'datePublished'    => $published,
            'dateModified'     => $modified,
            'author'           => ['@type' => 'Organization', 'name' => $siteName, 'url' => $base],
            'publisher'        => array_filter([
                '@type' => 'Organization',
                'name'  => $siteName,
                'logo'  => $logo ? ['@type' => 'ImageObject', 'url' => $logo] : null,
            ]),
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
