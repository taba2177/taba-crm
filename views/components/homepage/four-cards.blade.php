@props(['posts'])
@if(!empty($posts))
<section id="services"
  class="overflow-x-hidden bg-primary-color relative after:absolute after:top-10 after:left-5 after:w-650px after:h-550px after:blur-[150px] after:rounded-50% after:bg-gradient-circle-2 after:-z-1 after:-translate-x-1/2 after:opacity-60">
  <div class="py-60px md:py-20 lg:py-30 overflow-x-hidden">
    <div class="container">
      <!-- section heading -->
      {{-- <div class="max-w-560px">
        <div class="mb-25px">
          <span class="text-xs uppercase text-primary-color font-medium relative inline-block wow fadeInUp"
            data-wow-delay=".3s">{{ $posts->first()->postCategory->name}}</span>
        </div>
        <h2
          class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color wow fadeInUp"
          data-wow-delay=".4s">
          {{ $posts->first()->postCategory->description }}
        </h2>
      </div> --}}
      <!-- services grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($posts as $index => $post)
        @php
        $delays = [0.3, 0.5, 0.7, 0.9];
        $delay = $delays[$index % 4];
        @endphp
        <div
          class="rounded-25px relative overflow-hidden p-30px bg-cream-light-color dark:bg-bg-color-6 backdrop-blur-[40px] group transition-all duration-500 before:absolute before:left-0 before:top-0 before:rounded-25px before:w-0 before:h-0 before:transition-all before:duration-500 hover:before:w-full hover:before:h-full before:border-t before:border-l before:border-primary-color before:opacity-0 before:invisible hover:before:opacity-100 hover:before:visible after:absolute after:right-0 after:bottom-0 after:rounded-25px after:w-0 after:h-0 after:transition-all after:duration-500 hover:after:w-full hover:after:h-full after:border-b after:border-r after:border-primary-color after:opacity-0 after:invisible hover:after:opacity-100 hover:after:visible wow fadeInUp"
          data-wow-delay="{{ $delay }}s">
          <div class="mb-35px md:mb-75px">
            <span class="w-16 h-16 bg-primary-color rounded-20px inline-flex justify-center items-center leading-1">
              @if(!empty($post->icon))
              <i
                class="{{ $post->icon }} text-size-34 text-white-color leading-1 inline-flex transition-all duration-500 group-hover:scale-x-[-1]"></i>
              @else
              <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" alt="{{ $post->title ?? '' }}"
                class="w-8 h-8 object-contain" />
              @endif
            </span>
          </div>
          <div>
            <h3
              class="text-xl md:text-size-22 lg:text-2xl xl:text-size-22 2xl:text-2xl mb-15px leading-1.2 font-semibold text-seondary-color dark:text-white-color">
              {{ $post->title ?? '' }}
            </h3>
            <p class="text-primary-color-light dark:text-body-color mb-0 text-size-15">
              @if(!empty($post->excerpt))
              {{ $post->excerpt }}
              @else
              <span class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
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
              </span>
              @endif
            </p>
          </div>
          <a class="absolute top-0 left-0 w-full h-full z-1" href="{{ $post->url ?? '#' }}"></a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif
