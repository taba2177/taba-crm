@props(['posts'])
@if(!empty($posts))
<section id="portfolio"
  class="relative overflow-hidden after:absolute after:top-1/2 after:-translate-y-1/2 rtl:after:left-5 after:right-5 after:w-650px after:h-550px after:blur-[150px] after:rounded-50% after:bg-gradient-circle-2 after:-z-1 after:translate-x-1/2 after:opacity-60">
  <div class="pt-60px md:pt-100px lg:pt-30">
    <div class="container">
      <!-- section heading -->
      <div class="mb-10 md:mb-50px xl:mb-60px max-w-420px">
        <div class="tj_title_anim mb-25px">
          <span
            class="text-xs px-3 py-5px rounded-full border border-border-color-2 text-primary-color font-semibold tracking-0.2em relative inline-block uppercase">
            {{ $posts->first()->postCategory->name}}</span>
        </div>
        <h2
          class="tj_title_anim text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 capitalize font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color">
          {{ $posts->first()->postCategory->description }}
        </h2>
      </div>
      <!-- portfolios -->
      <div class="flex flex-wrap tj-project-4-wrappper">
        @foreach($posts as $index => $post)
        @php
        // Alternate animation and set delays as in your HTML
        $animation = ($index % 2 == 0) ? 'fadeInLeft' : 'fadeInRight';
        $delay = 0.3 + ($index * 0.2);
        @endphp
        <div
          class="tj-project-4-item w-full md:w-[calc(50%-25px)] lg:w-[calc(50%-30px)] xl:w-[calc(50%-63px)] mb-10 md:mb-60px xl:mb-20 [&:nth-child(2)]:mt-0 md:[&:nth-child(2)]:-mt-60 overflow-hidden wow {{ $animation }}"
          data-wow-delay=".{{ number_format($delay, 1) }}s">
          <div
            class="portfolio-item branding p-5 md:p-25px lg:p-35px bg-cream-light-color dark:bg-black-color hover:bg-primary-color-2 dark:hover:bg-seondary-color rounded-30px transition-all duration-300 group relative">
            <a href="{{ $post->url ?? '#' }}" class="mb-25px rounded-15px overflow-hidden">
              <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" class="transition-all duration-400"
                alt="{{ $post->title ?? '' }}" />
            </a>
            <div class="flex items-center gap-25px justify-between">
              <div>
                <h4
                  class="block text-xl text-seondary-color dark:text-white-color hover:text-primary-color dark:hover:text-primary-color font-bold mb-1.5">
                  <a href="{{ $post->url ?? '#' }}">{{ $post->title ?? '' }}</a>
                </h4>
                <p class="block text-primary-color-light dark:text-body-color-3">
                <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                  @foreach ($post->blocks as $block)
                  @switch($block->type)
                  @case('markdown')
                  @markdom($block->data->content)
                  @break
                  @case('figure')
                  <x-figure :image="$block->data->image" :alt="$block->data->alt" :caption="$block->data->caption" />
                  @break
                  @default
                  @dump($block)
                  @endswitch
                  @endforeach
                </div>
                </p>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif