<?php

namespace App\Livewire\Post;

use App\Concerns\HasPreview;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use Spatie\SchemaOrg\Schema;

class Show extends Component
{
    use HasPreview;

    /**
     * The post instance.
     *
     * @var \App\Models\Post
     */
    public $post;
    public $relatedPosts;

    /**
     * Mount the component.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function mount($category = null, $post)
    {
        if ($post === 'terms') {
            return view('livewire.terms');
        }

        $searchTerm = request('search');

        // $this->relatedPosts = Post::published()
        //     ->when($searchTerm, function ($query, $searchTerm) {
        //     $query->where('title', 'like', '%' . $searchTerm . '%')
        //           ->orWhere('content', 'like', '%' . $searchTerm . '%');
        //     })
        //     ->where('slug', '!=', $post)
        //     ->latest()
        //     ->where('slug', 'not like', '%alshot-oalahkam%')
        //     ->take(3)
        //     ->get();

        $this->post = Post::with('postCategory')->whereSlug($post)->firstOrFail();

        $this->handlePreview();

        // abort_unless($this->isPreview || $this->post->is_published, 404);

        // if ($category && $this->post->postCategory->slug !== str_replace('-', '_', $category)) {
        //     abort(404);
        // }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    // public function render()
    // {
    //     seo()
    //         ->title($this->title())
    //         ->description($this->desc())
    //         ->canonical($this->post->url)
    //         // ->twitterCard('summary')
    //         // ->twitterSite('@sunrollcurtains')
    //         // ->twitterTitle($this->title())
    //         // ->twitterDescription($this->desc())
    //         // ->twitterCreator('@sunrollcurtains')
    //         // ->twitterUrl($this->post->url)
    //         ->addSchema(
    //             Schema::article()
    //                 ->headline($this->post->title)
    //                 ->articleBody($this->post->excerpt)
    //                 ->image($this->post->image?->url)
    //                 ->datePublished($this->post->published_at)
    //                 ->dateModified($this->post->updated_at)
    //                 ->author(Schema::person()->name($this->post->user->name))
    //         );

    //     if ($this->post->image) {
    //         seo()->image($this->post->image->url);
    //     }

    //     $relatedProducts
    //      = Cache::remember('related_products_' . $this->post->id, 3600, function () {
    //         return Product::active()
    //             ->inRandomOrder()
    //             ->get();
    //         });

    //         // dd($this->post->postCategory->slug);

    //         if ($this->post->postCategory->slug === 'page') {
    //             return view('livewire.page', [ 'relatedProducts' => $relatedProducts,]);
    //         }

    //         return view('livewire.post.show',[
    //             'relatedProducts' => $relatedProducts,
    //         ]
    //     );
    // }

    public function render()
{
    seo()
        ->title($this->title())
        ->description($this->desc())
        ->canonical($this->post->url)
        ->addSchema(
            Schema::article()
                ->headline($this->post->title)
                ->articleBody($this->post->excerpt)
                ->image($this->post->image?->url)
                ->datePublished($this->post->published_at)
                ->dateModified($this->post->updated_at)
                ->author(Schema::person()->name($this->post->user->name))
        );

    if ($this->post->image) {
        seo()->image($this->post->image->url);
    }

    $postCategories = \App\Models\PostCategory::all();
    $view = 'livewire.post.show';

    foreach ($postCategories as $category) {
        if ($this->post->postCategory->slug === $category->slug) {
            $view = "livewire.post.{$this->post->postCategory->slug}.show";
            break;
        }
    }

    return view($view, [
        'post' => $this->post,
        'relatedPosts' => $this->relatedPosts
    ]);
}

public function title(): string
{
    $baseTitle = $this->post->title;
    $fullSuffix = ' | احدث الستائر والكنب والمجالس العربية';

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
