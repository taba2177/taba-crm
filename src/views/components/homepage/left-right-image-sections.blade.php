@props(['posts'])
@if(!empty($posts))
<section id="portfolio"
  class="relative z-0 after:absolute after:top-1/2 after:-translate-y-1/2 after:left-1/2 after:-translate-x-1/2 after:w-650px after:h-550px after:max-w-2/3 after:max-h-2/3 after:blur-[150px] after:rounded-50% after:bg-gradient-circle-2 after:-z-1 after:opacity-60">
  <div class="py-60px md:py-20 lg:py-30 overflow-x-hidden">

    <div class="container">
      <!-- section heading -->
      <div class="mb-10 md:mb-50px xl:mb-60px text-center">
        <div>
          <div class="mb-25px">
            <span
              class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em wow fadeInRight"
              data-wow-delay=".3s">
              {{ $posts->first()->postCategory->name ?? 'Behind the Pixels' }}
            </span>
          </div>
          <h2
            class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color max-w-580px w-full tj-text-invert">
            {{ $posts->first()->postCategory->description ?? 'Recent work for<br>MY clients' }}
          </h2>
        </div>
      </div>
      <!-- portfolios -->
      <div class="flex flex-col gap-50px md:gap-0">
        @foreach($posts as $index => $post)
        <div
          class="flex flex-col md:flex-row {{ $index % 2 == 1 ? 'md:flex-row-reverse' : '' }} items-center gap-30px lg:gap-60px xl:gap-75px 2xl:gap-40 overflow-hidden group">
          <div
            class="branding p-15px md:p-30px bg-cream-light-color dark:bg-black-color w-full max-w-[645px] rounded-15px transition-all duration-300 relative wow zoomIn"
            data-wow-delay=".3s" data-tilt>
            <a href="#" class="rounded-15px overflow-hidden">
              <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" class="transition-all duration-400"
                alt="{{ $post->title ?? '' }}" />
            </a>
          </div>
          <div class="w-full max-w-[500px] wow fadeInUp" data-wow-delay=".3s">
            <div>
              <h6
                class="text-size-70 md:text-size-75 lg:text-size-100 xl:text-124 font-bold transition-all duration-300 [-webkit-text-fill-color:transparent] [-webkit-text-stroke:1px_var(--primary-color)] dark:[-webkit-text-stroke:1px_var(--bg-color-2)] group-hover:[-webkit-text-stroke:1px_var(--primary-color)] dark:group-hover:[-webkit-text-stroke:1px_var(--primary-color)] opacity-30 dark:opacity-100 group-hover:opacity-100 mb-10px lg:mb-15px xl:mb-25px leading-1">
                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.
              </h6>
              <h4
                class="block text-3xl md:text-size-32 lg:text-size-40 xl:text-size-44 text-seondary-color dark:text-white-color hover:text-primary-color dark:hover:text-primary-color font-bold leading-1.2 tracking-0.02em uppercase mb-4 lg:mb-6">
                <a href="#">
                  {{ $post->title ?? '' }}
                </a>
              </h4>
              <p class="block text-primary-color-light dark:text-white-color mb-5">
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
            {{-- <a href="#"

              class="flex-shrink-0 text-size-25px w-50px h-50px lg:w-70px lg:h-70px text-primary-color-light dark:text-white-color group-hover:text-white-color bg-transparent group-hover:bg-gradient-primary-8 outline-1 outline outline-border-color dark:outline-bg-color-2 group-hover:outline-transparent dark:group-hover:outline-transparent rounded-100% leading-1 transition-all duration-300 inline-flex justify-center items-center">
              <span class="relative overflow-hidden -rotate-45">
                <i
                  class="fa-regular fa-arrow-right text-lg group-hover:translate-x-150% transition-all duration-500 inline-block"></i>
                <i
                  class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% text-lg group-hover:-translate-x-0 transition-all duration-500"></i>
              </span>
            </a> --}}

          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif