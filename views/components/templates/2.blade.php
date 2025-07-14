<x-layouts.main>

  <x-banner :title="$posts->first()->postCategory->name" :post="$posts->first()" backgroundImage="{{  $posts->first()->getRandomImage() }}">
    <x-breadcrumbs :items="[
            ['label' => 'الرئيسية', 'url' => route('home')],
            ['label' => $posts->first()->postCategory->name, 'url' => route('dynamic.route', [$posts->first()->postCategory->slug])],
            ['label' => $posts->first()->postCategory->description, 'url' => '']]" />

  </x-banner>
  <!-- faq area -->
  <section id="{{ $posts->first()->postCategory->slug }}">
    <div class="py-20 md:py-100px xl:py-30 ">
      <div class="container">
        <!-- section heading -->
        {{-- <div class="mb-10 md:mb-50px xl:mb-60px text-center">
          <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 inline-block bg-gradient-text-light dark:bg-gradient-text bg-clip-text leading-1.2 text-transparent wow fadeInUp" data-wow-delay=".3s">
            {{ __('Your Questions and Answers') }}
        </h2>
      </div> --}}
      <div class="faq max-w-1120px mx-auto wow fadeInUp" data-wow-delay="0.3s">
        <div class="w-full flex flex-col gap-25px" id="accordion">
          @foreach($posts as $index => $post)
          <!-- item  -->
          <div class=" accordion-single transition-all duration-300 border bg-cream-light-color dark:bg-transparent border-body-color dark:border-seondary-color rounded-15px ">
            <button class="accordion-btn text-primary-color  dark:text-white-color flex justify-between items-center w-full text-left rtl:text-right md:rtl:right-[30px] right-[15px] text-base md:text-xl py-4 px-15px pr-10 rtl:pr-auto rtl:pl-10 md:py-21px md:px-30px rtl:before:left-4 rtl:before:right-auto md:rtl:before:right-auto  {{ $index == 0 ? 'open' : '' }}">
              <span class="text-lg font-medium">
                {{ $post->title }}
              </span>
            </button>
            <div class="accordion-content {{ $index == 0 ? 'open' : '' }}">
              <div class="pt-5px pb-30px px-5 md:pl-30px rtl:md:pr-30px rtl:md:pl-auto ">
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
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    </div>
  </section>
</x-layouts.main>
