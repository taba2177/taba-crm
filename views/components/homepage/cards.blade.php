@props(['posts'])
@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}" class="bg-secondary-color">
  <div class="pb-20 md:pb-100px xl:pb-30">
    <div class="container">
      <!-- section heading -->
      <div class="mb-10 md:mb-50px xl:mb-60px text-center">
        <h2
          class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 inline-block bg-gradient-text-light dark:bg-gradient-text bg-clip-text leading-1.2 text-transparent wow fadeInUp"
          data-wow-delay=".3s">
          {{ $posts->first()->postCategory->name ?? 'Services' }}
        </h2>
        <p class="uppercase text-seondary-color dark:text-white-color mt-15px wow fadeInUp" data-wow-delay="0.5s">
          {{ $posts->first()->postCategory->description ?? 'Offered Services' }}
        </p>
      </div>
      <!-- services -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-30px">
        @foreach($posts as $index => $post)
        @php
        $delay = 0.3 + ($index * 0.1);
        // Optionally, map post category or type to icon class
        $icons = [
        0 => 'flaticon-design',
        1 => 'flaticon-ux-design',
        2 => 'flaticon-web-design',
        3 => 'flaticon-3d-movie',
        ];
        $icon = $icons[$index % count($icons)];
        @endphp
        <div
          class="rounded-15px overflow-hidden py-25px px-15px md:p-35px md:pt-25px bg-transparent hover:bg-primary-color dark:hover:bg-primary-color border border-border-color dark:border-primary-color hover:border-primary-color dark:hover:border-white-color group transition-all duration-300 wow fadeInUp"
          data-wow-delay="{{ number_format($delay, 1) }}s">
          <div class="mb-25px">
            <i class="{{ $icon }} text-size-32 text-primary-color group-hover:text-white-color leading-1"></i>
          </div>
          <div>
            <h3 class="text-xl mb-10px leading-1.2 font-medium">
              <a href="{{ $post->url ?? '#' }}"
                class="text-primary-color dark:text-white-color hover:text-primary-color group-hover:text-white-color dark:group-hover:text-white-color">
                {{ $post->title ?? '' }}
              </a>
            </h3>
            <p
              class="text-primary-color-light dark:text-white-color group-hover:text-white-color dark:group-hover:text-white-color mb-0">
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
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif