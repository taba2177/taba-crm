@props([
'posts' => [],
])
<div class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
  <div class="blog-warp">
    <a href="{{ $post->url }}" class="group" title="{{ $post->title }}">
      <div class="w-full h-48 mb-2 overflow-hidden border rounded-lg group-hover:brightness-90">
        @if ($post->image)
        <img class="object-cover w-full h-full rounded" src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->image->alt ?? $post->title }}" />
        @else
        <div class="flex items-center justify-center w-full h-full text-gray-400 bg-gray-200">
          Article
        </div>
        @endif
      </div>

      <div class="meta-box">
        <a href="#" title="{{ $post->title }}">{{ $post->category }}</a> <span>/</span> {{ $post->date }}
      </div>
      <h4 class="h4-md mb-3 text-lg text-gray-700 group-hover:text-primary-500">
        {{ $post->title }}
      </h4>

      <p class="text-gray-600">
        {{ $post->excerpt }}
      </p>
    </a>
  </div>
</div>
