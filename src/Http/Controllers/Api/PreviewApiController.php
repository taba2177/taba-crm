<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\PostCategoryResource;
use Taba\Crm\Http\Resources\Api\PostResource;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class PreviewApiController extends ApiController
{
    public function home(string $key): JsonResponse
    {
        $preview = Cache::get("preview:{$key}");

        if (!$preview) {
            return $this->notFound('Preview expired');
        }

        $type = $preview['type'] ?? 'category';

        if ($type === 'category') {
            return $this->categoryPreview($preview);
        }

        return $this->postPreview($preview);
    }

    private function categoryPreview(array $preview): JsonResponse
    {
        $formData = $preview['data'];
        $categoryId = $preview['id'];
        $locale = app()->getLocale();

        $categories = PostCategory::where('register_in_header', true)
            ->withCount('posts')
            ->orderBy('order')
            ->get();

        $sections = $categories->map(function ($category) use ($categoryId, $formData, $locale) {
            $section = [
                'id'                => $category->id,
                'name'              => $category->getTranslation('name', $locale, false),
                'slug'              => $category->slug,
                'description'       => $category->getTranslation('description', $locale, false),
                'subtitle'          => $category->getTranslation('subtitle', $locale, false),
                'section_component' => $category->section_component,
                'order'             => $category->order,
            ];

            if ($category->id === $categoryId) {
                $section['name'] = $formData['name'] ?? $section['name'];
                $section['description'] = $formData['description'] ?? $section['description'];
                $section['subtitle'] = $formData['subtitle'] ?? $section['subtitle'];
                $section['section_component'] = $formData['section_component'] ?? $section['section_component'];
            }

            $posts = Post::where('post_category_id', $category->id)
                ->where('show_in_home', true)
                ->with(['postCategory', 'image', 'tags'])
                ->orderBy('order')
                ->get()
                ->map(fn ($post) => $this->serializePost($post, $locale));

            $section['posts'] = $posts;

            return $section;
        });

        $featuredPosts = Post::published()
            ->where('show_in_home', true)
            ->with(['postCategory', 'image', 'tags'])
            ->orderBy('order')
            ->get()
            ->map(fn ($post) => $this->serializePost($post, $locale));

        return $this->success([
            'sections' => $sections,
            'featured_posts' => $featuredPosts,
            '_preview' => true,
        ]);
    }

    private function postPreview(array $preview): JsonResponse
    {
        $formData = $preview['data'];
        $postId = $preview['id'];
        $locale = app()->getLocale();

        $post = Post::with(['postCategory', 'image', 'tags'])->findOrFail($postId);

        $postData = $this->serializePost($post, $locale);
        $postData['title'] = $formData['title'] ?? $postData['title'];
        $postData['content'] = $formData['content'] ?? $postData['content'];
        $postData['meta_title'] = $formData['meta_title'] ?? $postData['meta_title'];
        $postData['meta_description'] = $formData['meta_description'] ?? $postData['meta_description'];
        $postData['icon'] = $formData['icon'] ?? $postData['icon'];
        $postData['homepage_section_component'] = $formData['homepage_section_component'] ?? $post->homepage_section_component;

        $category = $post->postCategory;
        $categorySlug = $category?->slug;

        $categoryId = $post->post_category_id;
        $categories = PostCategory::where('register_in_header', true)
            ->withCount('posts')
            ->orderBy('order')
            ->get();

        $sections = $categories->map(function ($cat) use ($categoryId, $postData, $locale, $postId) {
            $section = [
                'id'                => $cat->id,
                'name'              => $cat->getTranslation('name', $locale, false),
                'slug'              => $cat->slug,
                'section_component' => $cat->section_component,
                'order'             => $cat->order,
            ];

            $posts = Post::where('post_category_id', $cat->id)
                ->where('show_in_home', true)
                ->with(['postCategory', 'image', 'tags'])
                ->orderBy('order')
                ->get()
                ->map(function ($p) use ($postId, $postData, $locale) {
                    if ($p->id === $postId) {
                        return $postData;
                    }
                    return $this->serializePost($p, $locale);
                });

            $section['posts'] = $posts;
            return $section;
        });

        $featuredPosts = Post::published()
            ->where('show_in_home', true)
            ->with(['postCategory', 'image', 'tags'])
            ->orderBy('order')
            ->get()
            ->map(function ($p) use ($postId, $postData, $locale) {
                if ($p->id === $postId) {
                    return $postData;
                }
                return $this->serializePost($p, $locale);
            });

        return $this->success([
            'sections' => $sections,
            'featured_posts' => $featuredPosts,
            '_preview' => true,
        ]);
    }

    private function serializePost(Post $post, string $locale): array
    {
        return [
            'id'          => $post->id,
            'title'       => $post->getTranslation('title', $locale, false),
            'slug'        => $post->slug,
            'content'     => $post->getTranslation('content', $locale, false),
            'excerpt'     => $post->excerpt,
            'image_url'   => $post->image?->url ?? null,
            'icon'        => $post->icon,
            'meta_title'  => $post->getTranslation('meta_title', $locale, false),
            'meta_description' => $post->getTranslation('meta_description', $locale, false),
            'metadata'    => $post->getTranslation('metadata', $locale, false),
            'show_in_home' => $post->show_in_home,
            'order'       => $post->order,
            'image'       => $post->image ? [
                'id'  => $post->image->id,
                'url' => $post->image->url,
            ] : null,
        ];
    }
}
