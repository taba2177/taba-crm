<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $categories = PostCategory::where('register_in_header', true)
            ->with(['firstPost', 'children'])
            ->withCount('posts')
            ->orderBy('order')
            ->get();

        $sections = $categories->map(function ($category) use ($categoryId, $formData) {
            $resource = (new PostCategoryResource($category))->toArray(request());

            if ($category->id === $categoryId) {
                $resource['name'] = $formData['name'] ?? $resource['name'];
                $resource['description'] = $formData['description'] ?? $resource['description'];
                $resource['subtitle'] = $formData['subtitle'] ?? $resource['subtitle'];
                $resource['section_component'] = $formData['section_component'] ?? $resource['section_component'];
            }

            $posts = Post::where('post_category_id', $category->id)
                ->where('show_in_home', true)
                ->with(['postCategory', 'image', 'tags'])
                ->orderBy('order')
                ->get();

            $resource['posts'] = PostResource::collection($posts)->toArray(request());

            return $resource;
        });

        return $this->success([
            'sections' => $sections,
            '_preview' => true,
        ]);
    }

    private function postPreview(array $preview): JsonResponse
    {
        $formData = $preview['data'];
        $postId = $preview['id'];
        $locale = app()->getLocale();

        $post = Post::with(['postCategory', 'image', 'tags'])->findOrFail($postId);

        $postData = (new PostResource($post))->toArray(request());
        $postData['title'] = $formData['title'] ?? $postData['title'];
        $postData['content'] = $formData['content'] ?? $postData['content'];
        $postData['meta_title'] = $formData['meta_title'] ?? $postData['meta_title'];
        $postData['meta_description'] = $formData['meta_description'] ?? $postData['meta_description'];
        $postData['icon'] = $formData['icon'] ?? $postData['icon'];

        $category = $post->postCategory;
        $categoryData = $category ? (new PostCategoryResource($category))->toArray(request()) : null;

        $relatedPosts = $post->relatedPosts()->published()->latest()->take(4)->get();

        return $this->success([
            'post' => $postData,
            'category' => $categoryData,
            'relatedPosts' => PostResource::collection($relatedPosts)->toArray(request()),
            '_preview' => true,
        ]);
    }
}
