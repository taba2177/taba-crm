<?php

namespace Taba\Crm\Http\Controllers;

use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Spatie\SchemaOrg\Schema;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public $post;
    public $categorySlug;
    public $category;

    public function index($categorySlug)
    {
        // get all categories from cache
        $categories = PostCategory::RegisterInHeader();
        $category = PostCategory::where('slug', $categorySlug)->firstOrFail();
        $this->category=$category;
        $posts = Post::published()->with('postCategory')->where('post_category_id', $category->id)->orderBy('order')->get();

        $this->post = null;
        $this->categorySlug = $categorySlug;
        $this->setSeoMetadata();

        return view('posts.index', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'layout' => 'layouts.main'
        ]);
    }

    public function category(PostCategory $category)
    {
        $posts = $category->posts()->published()->orderBy('order')->get();

        return view('posts.category', [
            'category' => $category,
            'posts' => $posts,
            'layout' => 'layouts.main'
        ]);
    }

    public function show($category,$post)
    {
        $category = PostCategory::where('slug', $category)->firstOrFail();
        $post = Post::where('slug', $post)->firstOrFail();

        abort_unless($post->post_category_id === $category->id, 404);
        abort_unless($post ?? null, 404);
        abort_unless($post->is_published || auth()->check(), 404);

        // $view = "livewire.post.{$category->order}.show";
        $view = "livewire.post.templates.{$post->homepage_section_component}";


        if (!view()->exists($view)) {
            $view = "livewire.post.default.show";
        }

        $this->post = $post;
        $this->categorySlug = $category->slug;

        $this->setSeoMetadata();

        return view($view, [
            'post' => $post,
            'relatedPosts' => $this->getRelatedPosts($post),
            'layout' => 'layouts.main'
        ]);
    }

    protected function getRelatedPosts(Post $post)
    {
        return \Taba\Crm\Models\Post::published()
            ->forCategory($post->postCategory->slug)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();
    }


    protected function setSeoMetadata()
    {
        if (!$this->post) {
            seo()
                ->title($this->category->name .' '. $this->category->subtitle)
                ->description($this->category->description .' '. $this->category->subtitle)
                ->canonical($this->categorySlug)
                ->addSchema(
                    Schema::webPage()
                        ->name($this->category->name) // Use post title for WebPage name
                        ->description($this->category->description)
                        ->url($this->categorySlug) // Use the post's direct URL
                        ->author(Schema::organization()->name(config('app.name')))
                    );
                    return;
        }
        // Initialize the Schema variable
        $Schema = null;

        // Get the category slug safely. If the post has no category, it will be an empty string.
        $categorySlug = $this->post->postCategory->slug ?? '';

        // 1. Check if the category slug contains the word "service"
        if (Str::contains($categorySlug, 'service')) {

            $Schema = Schema::service()
                ->name($this->post->title)
                // Safely get description: check meta_description, then excerpt, then content
                ->description($this->post->meta_description ?: $this->post->blocks[0]->data->content)
                ->url($this->post->url)
                ->image($this->post->image?->url)
                ->provider(
                    Schema::organization()->name(config('app.name'))
                )
                ->areaServed(
                    Schema::place()->name('جدة')
                )
                ->category($this->post->postCategory->name);

        // 2. Check if the category slug contains "blog" or "article"
        } elseif (Str::contains($categorySlug, ['blog', 'article'])) {

            $Schema = Schema::article()
                ->headline($this->post->title)
                ->articleBody($this->post->meta_description ?: $this->post->blocks[0]->data->content)
                ->image($this->post->image?->url)
                ->datePublished($this->post->published_at)
                ->dateModified($this->post->updated_at)
                ->author(Schema::person()->name($this->post->user->name));

        // 3. Fallback for all other categories
        } else {

            $Schema = Schema::webPage()
                ->name($this->post->title) // Use post title for WebPage name
                ->description($this->post->meta_description ?: $this->post->blocks[0]->data->content)
                ->url($this->post->url) // Use the post's direct URL
                ->author(Schema::organization()->name(config('app.name')));
        }

        // Apply the selected schema to the SEO settings
        if ($Schema) {
            seo()
                ->title($this->title())
                ->description($this->desc())
                ->canonical($this->post->url)
                ->addSchema($Schema);
        }

    }

        public function title(): string
        {
            $baseTitle = $this->post->title;
            $fullSuffix = ' | '.$this->post->postCategory->name;
            $baseLength = mb_strlen($baseTitle, 'UTF-8');
            $fullSuffixLength = mb_strlen($fullSuffix, 'UTF-8');

            // Case 1: Entire title + full suffix fits
            if ($baseLength + $fullSuffixLength <= 60) {
                return $baseTitle . $fullSuffix;
            }

            // Case 2: Just base title fits completely
            if ($baseLength <= 60) {
                // Find how much suffix we can include
                $availableSpace = 60 - $baseLength;

                // Only include suffix if we can show at least one complete word
                if ($availableSpace >= 7) { // Length of shortest word in suffix
                    // Find the last space before the cutoff
                    $partialSuffix = mb_substr($fullSuffix, 0, $availableSpace, 'UTF-8');
                    $lastSpace = mb_strrpos($partialSuffix, ' ', 0, 'UTF-8');

                    return $lastSpace !== false
                        ? $baseTitle . mb_substr($fullSuffix, 0, $lastSpace, 'UTF-8')
                        : $baseTitle;
                }

                return $baseTitle;
            }

            // Case 3: Base title needs trimming
            $trimmed = mb_substr($baseTitle, 0, 60, 'UTF-8');
            $lastSpace = mb_strrpos($trimmed, ' ', 0, 'UTF-8');

            return $lastSpace !== false
                ? mb_substr($trimmed, 0, $lastSpace, 'UTF-8')
                : $trimmed;
        }

        public function desc(): string {
            $excerpt = $this->post->excerpt;
            $idealLength = 150; // Optimal for meta descriptions
            $tolerance = 5; // ±5 characters flexibility
            $minLength = $idealLength - $tolerance;
            $maxLength = $idealLength + $tolerance;

            // If already within ideal range
            $currentLength = mb_strlen($excerpt, 'UTF-8');
            if ($currentLength <= $maxLength) {
                return $excerpt;
            }

            // Start with a safe initial cut (longer than needed)
            $initialCut = min($maxLength + 30, $currentLength);
            $workingText = mb_substr($excerpt, 0, $initialCut, 'UTF-8');

            // Arabic-specific break points in priority order
            $breakPoints = [
                '. ',   // Sentence end
                '، ',   // Arabic comma
                '؛ ',   // Arabic semicolon
                ' - ',  // Dash
                ' ',    // Word boundary
            ];

            // Try to find the best break point
            foreach ($breakPoints as $breakPoint) {
                $pos = $this->findOptimalBreak($workingText, $breakPoint, $minLength, $maxLength);

                if ($pos !== false) {
                    $cleanCut = mb_substr($excerpt, 0, $pos, 'UTF-8');

                    // Ensure we're not leaving just 1-2 words after cut
                    $remainingText = mb_substr($excerpt, $pos, null, 'UTF-8');
                    $remainingWords = count(array_filter(explode(' ', $remainingText)));

                    if ($remainingWords > 1 || mb_strlen($remainingText, 'UTF-8') > 10) {
                        return rtrim($cleanCut) . '.';
                    }
                }
            }

            // Final fallback - cut at last complete word before max length
            $lastSpace = mb_strrpos(mb_substr($excerpt, 0, $maxLength, 'UTF-8'), ' ', 0, 'UTF-8');
            if ($lastSpace !== false && $lastSpace >= $minLength) {
                return mb_substr($excerpt, 0, $lastSpace, 'UTF-8') . '.';
            }

            // Absolute fallback - hard cut with ellipsis
            return mb_substr($excerpt, 0, $idealLength - 3, 'UTF-8') . '.';
        }

        private function findOptimalBreak(string $text, string $breakPoint, int $min, int $max): int|false {
            $pos = mb_strrpos($text, $breakPoint, 0, 'UTF-8');

            // Find the last break point within our desired range
            while ($pos !== false) {
                if ($pos >= $min && $pos <= $max) {
                    return $pos + mb_strlen($breakPoint, 'UTF-8');
                }

                if ($pos < $min) {
                    return false;
                }

                // Look for earlier occurrence
                $text = mb_substr($text, 0, $pos, 'UTF-8');
                $pos = mb_strrpos($text, $breakPoint, 0, 'UTF-8');
            }

            return false;
        }
}