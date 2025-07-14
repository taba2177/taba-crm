@props(['posts'])

@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}" class="bg-primary-color pb-50px">

  <div class="pt-60px pb-60px md:pt-20 md:pb-60px lg:pt-100px lg:pb-20 bg-primary-color">
    <div class="container">
      <!-- section heading -->
      <x-section-header-dark title="{{ $posts->first()->postCategory->name ?? '' }}" description="{{ $posts->first()->postCategory->description ?? '' }}" />


      <!-- services -->
      <div class="services">
        <div class="flex flex-wrap items-center justify-center gap-9">
          @foreach($posts as $index => $item)
          <!-- service single -->
          <div class="w-[450px] group wow fadeInUp" data-wow-delay="{{ 0.3 + ($index * 0.1) }}s">
            <!-- contents -->
            <a href="#" class="flex flex-col py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
              <div class="mb-5 md:mb-30px w-60px rounded flex flex-col justify-start items-center">
                <img class="grayscale-[50%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 w-60px h-60px object-cover rounded-full animate-bounce animate-infinite" src="{{ asset($item->image?->url ?? $item->getRandomImage()) }}" alt="{{$item->title}}" style="animation-delay: {{ 0.2 * $index }}s; animation-duration: {{ 1 + ($index * 0.2) }}s;" />
              </div>
              <div class="text-2xl text-bold mb-1 text-gray-color-2 group-hover:text-primary-color transition-none duration-300">
                {{$item->title }}
              </div>
              <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                @foreach ($item->blocks as $block)
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

            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
@endif
