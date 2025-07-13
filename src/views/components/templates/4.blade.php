<x-layouts.main>
  <x-banner :title="$posts->first()->postCategory->name" :post="$posts->first()" backgroundImage="{{ $posts->first()->getRandomImage() }}">


    <x-breadcrumbs :items="[
                                ['label' => 'الرئيسية', 'url' => route('home')],
                                ['label' => $posts->first()->postCategory->name, 'url' => route('dynamic.route', [$posts->first()->postCategory->slug])],
                                ['label' => $posts->first()->postCategory->description, 'url' => '']
                                ]" />
  </x-banner>

  <section id="{{ $posts->first()->postCategory->slug }}" class="py-60px md:py-20 lg:py-100px xl:py-30">
    <div class="container">
      <!-- section heading -->
      {{-- <div class="text-center flex flex-col items-center mb-10 md:mb-50px">
        <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px wow fadeInUp" data-wow-delay=".3s">
          {{ $posts->first()->postCategory->name }}
      </h2>
      <p class="text-primary-color-light dark:text-body-color max-w-700px wow fadeInUp" data-wow-delay=".4s">
        {{ $posts->first()->postCategory->description }}
      </p>
    </div> --}}
    <!-- blogs -->
    <div class="tj-blog-7-main-wrapper">
      @foreach($posts as $post)
      <div class="tj-blog-7-wrapper relative wow fadeInUp" data-wow-delay="{{ 0.2 * $loop->index + 0.3 }}s">
        <div class="tj-blog-7-thumb">
          <a href="{{ $post->url }}" class="">
            <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="w-full" />
          </a>
        </div>

        <div class="tj-blog-7-content">
          <div class="tj-blog-7-meta">
            <span><a href="#">{{ $post->tags->isNotEmpty() ? $post->tags->first()->name : $post->postCategory->name }}</a></span>
            {{-- <span class="date">{{ $post->published_at->translatedFormat('M d, Y') }}</span> --}}
          </div>
          <h4 class="tj-blog-7-title">
            <a href="{{ $post->url }}">{{ $post->title }}</a>
          </h4>
          <div class="tj-blog-6-button">
            <a href="{{ $post->url }}" class="text-primary-color-light dark:text-white-color transition-all duration-300 inline-flex gap-x-2 items-center bg-[0_100%] [background-size:0_1px] bg-no-repeat bg-gradient-primary-11 hover:[background-size:100%_1px] leading-1.5 group/nested">
              {{ __('Read More') }}
              <span class="relative overflow-hidden -rotate-45">
                <i class="fa-regular fa-arrow-right group-hover/nested:translate-x-150% transition-all duration-500 inline-block"></i>
                <i class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% group-hover/nested:-translate-x-0 transition-all duration-500"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    </div>
  </section>
</x-layouts.main>
