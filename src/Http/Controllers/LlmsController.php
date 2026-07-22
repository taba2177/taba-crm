<?php

namespace Taba\Crm\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

/**
 * Serves a spec-compliant `/llms.txt` (see https://llmstxt.org) so AI agents
 * and crawlers get a concise, link-rich map of the site. Addresses the
 * PageSpeed "agent accessibility" audit.
 */
class LlmsController extends Controller
{
    public function __invoke(): Response
    {
        $locale = app()->getLocale();

        $body = Cache::remember('llms_txt_' . $locale, config('crm.api.cache_ttl', 300), function () use ($locale) {
            return $this->build($locale);
        });

        return response($body, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    private function build(string $locale): string
    {
        $settings = CrmSetting::getAllGrouped();

        $str = function ($value, string $default = '') use ($locale): string {
            if (is_array($value)) {
                return (string) ($value[$locale] ?? $value['en'] ?? $value['ar'] ?? $default);
            }
            return (string) ($value ?? $default);
        };

        $business    = $settings['business'] ?? [];
        $seo         = $settings['seo'] ?? [];
        $name        = $str($business['crm_business_name'] ?? null, config('app.name'));
        $description = $str($business['crm_business_description'] ?? null,
            $str($seo['crm_seo_default_description'] ?? null, ''));

        $lines = [];
        $lines[] = '# ' . $name;
        $lines[] = '';
        if ($description !== '') {
            $lines[] = '> ' . $this->oneLine($description);
            $lines[] = '';
        }
        $lines[] = 'Website: ' . url('/');
        $lines[] = '';

        // Sections (categories registered in the header/navigation).
        $categories = PostCategory::where('register_in_header', true)
            ->orderBy('order')
            ->get();

        if ($categories->isNotEmpty()) {
            $lines[] = '## Sections';
            $lines[] = '';
            foreach ($categories as $category) {
                $catName = $str($category->getTranslation('name', $locale, false), $category->slug);
                $catDesc = $this->oneLine($str($category->getTranslation('description', $locale, false), ''));
                $url = url($category->slug);
                $lines[] = "- [{$catName}]({$url})" . ($catDesc !== '' ? ": {$catDesc}" : '');
            }
            $lines[] = '';
        }

        // Recent published content.
        $posts = Post::published()
            ->with('postCategory')
            ->orderByDesc('published_at')
            ->limit(50)
            ->get()
            ->filter(fn (Post $p) => $p->postCategory !== null);

        if ($posts->isNotEmpty()) {
            $lines[] = '## Content';
            $lines[] = '';
            foreach ($posts as $post) {
                $title = $str($post->getTranslation('title', $locale, false), $post->slug);
                $url   = url($post->postCategory->slug . '/' . $post->slug);
                $desc  = $this->oneLine($str($post->getTranslation('meta_description', $locale, false), ''));
                $lines[] = "- [{$title}]({$url})" . ($desc !== '' ? ": {$desc}" : '');
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /** Collapse whitespace/newlines so each Markdown list item stays on one line. */
    private function oneLine(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
    }
}
