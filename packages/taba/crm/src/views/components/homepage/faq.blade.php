@props(['posts'])
@if(!empty($posts))
<section id="{{ $posts->first()->postCategory->slug }}">
  <div class="py-20 md:py-100px xl:py-30 bg-cream-light-color dark:bg-black-color">
    <div class="container">
      <!-- section heading -->
      <x-section-header title="{{ $posts->first()->postCategory->name ?? '' }}"
        description="{{ $posts->first()->postCategory->description ?? '' }}" />
      <!-- faq -->
      <div class="faq max-w-1120px mx-auto wow fadeInUp" data-wow-delay="0.3s">
        <div class="w-full flex flex-col gap-25px" id="accordion">
          @foreach($posts as $index => $post)
          <!-- item  -->
          <div
            class=" accordion-single transition-all duration-300 border bg-cream-light-color dark:bg-transparent border-body-color dark:border-seondary-color rounded-15px ">
            <button
              class="accordion-btn text-primary-color  dark:text-white-color flex justify-between items-center w-full text-left rtl:text-right md:rtl:right-[30px] right-[15px] text-base md:text-xl py-4 px-15px pr-10 rtl:pr-auto rtl:pl-10 md:py-21px md:px-30px rtl:before:left-4 rtl:before:right-auto md:rtl:before:right-auto  {{ $index == 0 ? 'open' : '' }}">
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
@endif