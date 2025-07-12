<x-layouts.main>
  <x-banner :title="$posts->first()->postCategory->name" :post="$posts->first()" backgroundImage="{{  $posts->first()->getRandomImage() }}">

    <x-breadcrumbs :items="[
            ['label' => 'الرئيسية', 'url' => route('home')],
            ['label' => $posts->first()->postCategory->name, 'url' => route('dynamic.route', [$posts->first()->postCategory->slug])],
            ['label' => $posts->first()->postCategory->description, 'url' => '']]" />

  </x-banner>

  <section id="{{ $posts->first()->postCategory->slug }}">

    <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-30 overflow-x-hidden">
      <div class="container">
        <div class="">
          <div class="flex items-center mb-30px sm:mb-10">
            <ul id="tabs" class="max-w-full mx-auto inline-flex items-center justify-center bg-primary-color rounded-full p-5px relative z-0">
              @foreach($posts as $index => $post)
              <li class="{{ $loop->first ? 'active' : '' }}">
                <a class="text-sm sm:text-size-15 font-bold px-15px sm:px-25px py-10px sm:py-11px text-white-color bg-transparent rounded-full" href="#{{ Str::slug($post->title) }}">
                  {{ $post->title }}
                </a>
              </li>
              @endforeach
            </ul>
          </div>

          <div id="tab-contents">
            @foreach($posts as $index => $post)
            <div id="{{ Str::slug($post->title) }}" class="tab-contents tab-pane fade {{ $loop->first ? 'show active' : '' }}">
              <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 md:gap-30px">
                <div class="rounded-15px relative p-30px bg-white-color dark:bg-bg-color-6 backdrop-blur-[40px] z-0 group transition-all duration-500 before:absolute before:left-0 before:top-0 before:rounded-15px before:w-0 before:h-0 before:transition-all before:duration-500 hover:before:w-full hover:before:h-full before:border-t before:border-l before:border-primary-color before:opacity-0 before:invisible hover:before:opacity-100 hover:before:visible before:-z-1 after:absolute after:right-0 after:bottom-0 after:rounded-15px after:w-0 after:h-0 after:transition-all after:duration-500 hover:after:w-full hover:after:h-full after:border-b after:border-r after:border-primary-color after:opacity-0 after:invisible hover:after:opacity-100 hover:after:visible after:-z-1">
                  <div class="mb-35px">
                    <span class="w-16 h-16 bg-gradient-secondary bg-200 rounded-100% inline-flex justify-center items-center leading-1">
                      <i class="flaticon-design text-size-34 text-white-color leading-1 inline-flex transition-all duration-500 group-hover:scale-x-[-1]"></i>
                    </span>
                  </div>
                  <h3 class="tj-service-5-accordion-list-title text-xl sm:text-size-22 md:text-3xl font-bold mb-15px leading-1.2 md:leading-1.2 tracking-0.02em transition-all duration-300 ease-in relative">
                    <div class="text-seondary-color dark:text-white-color hover:text-primary-color dark:hover:text-primary-color">
                      {{ $post->title }}
                    </div>
                  </h3>
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
                  <div class="absolute m-4 right-0 left-0 bottom-0 bg-gradient-secondary bg-200 rounded-15px p-3 inline-flex justify-center items-center leading-1">
                    <a href="/" class="text-xl text-white-color leading-1 transition-all duration-300 inline-flex justify-center items-center hover:text-primary-color">
                      اشتراك
                      <span class="relative overflow-hidden px-2 justify-end -rotate-45">
                        <i class="fa-regular fa-arrow-right group-hover:translate-x-150% transition-all duration-500 inline-block"></i>
                        <i class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% group-hover:-translate-x-0 transition-all duration-500"></i>
                      </span>
                    </a>
                  </div>
                </div>
                {{-- post image  --}}
                <div class="overflow-hidden col-span-2 px-15px md:px-25px lg:px-10 bg-cream-light-color dark:bg-primary-color-light">
                  <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="w-full rounded" />
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layouts.main>
