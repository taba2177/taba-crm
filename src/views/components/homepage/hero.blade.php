@props(['posts'])
@if(!empty($posts))
@php
$post =$posts[0];
@endphp
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new PureCounter({
      selector: '.purecounter',
      duration: 2,
      delay: 10,
      once: true
    });
  });
</script>
@endpush
<section class="hero-section relative pt-130px lg:pt-40 xl:pt-200px pb-10 md:pb-30px lg:pb-50px after:absolute after:top-0 after:right-0 after:w-322px after:h-308px after:blur-[150px] after:rounded-50% after:bg-gradient-circle after:-z-1 after:-mt-5% after:-mr-5% overflow-hidden">
  <!-- intro tex -->
  <div class="intro_text">
    <svg viewBox="0 0 1320 300" class="overflow-hidden">
      <text x="50%" y="50%" text-anchor="middle" class="animate-stroke">
        HI
      </text>
    </svg>
  </div>
  <div class="container">
    <div class="hidded md:grid md:grid-cols-2 md:items-center gap-30px">
      <div>
        <h4 class="text-seondary-color dark:text-body-color text-size-22 md:text-size-25 lg:text-4xl lg:leading-1.5 font-bold mb-1.5 xl:mb-10px">
          {{$post->title ?? 'title'}}
        </h4>
        <h1 class="text-size-35 md:text-size-38 lg:text-size-50 xl:text-6xl 2xl:text-size-65 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
          {{$post->subtitle ?? 'sub'}}
        </h1>
        <div class="flex md:hidden justify-center items-center my-30px">
          <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" alt="banner image" class="rounded-38px border-2 border-seondary-color hover:border-primary-color rotate-[4.29deg] hover:rotate-0 transition-all duration-300 max-w-[80%]" />


        </div>
        {{-- <p class="text-xl leading-1.5 text-primary-color-light dark:text-body-color max-w-540px"> --}}
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

        <!-- action and social -->
        <div class="flex items-center gap-30px lg:gap-25px mt-5 flex-wrap lg:flex-nowrap md:mt-30px lg:mt-50px">
          <div>
            <a href="#" class="text-size-15 dark:text-body-color font-medium text-primary-color hover:text-body-color capitalize py-17px px-35px bg-200 bg-transparent hover:bg-primary-color rounded-full leading-1 border border-primary-color text-nowrap tracking-1px">

              خدمات التسويق
              <i class="flaticon-download ml-0.5 text-size-17"></i></a>
          </div>
          <div>
            <ul class="flex gap-x-5">
              <li>
                <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-light fa-basketball"></i></a>
              </li>
              <li>
                <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-linkedin-in"></i></a>
              </li>
              <li>
                <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-github"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="hidden md:flex md:justify-center md:items-center relative after:absolute after:bottom-0 after:left-0 after:w-220px after:h-220px after:blur-[150px] after:rounded-50% after:bg-gradient-circle after:-z-1 after:-mt-5% after:-mr-5%">
        <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" alt="banner image" class="rounded-38px border-2 border-seondary-color hover:border-primary-color rotate-[4.29deg] hover:rotate-0 transition-all duration-300" />
      </div>
      <div class="group_overly">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- fanfact -->
  <div class="funfact-area mt-60px xl:mt-70px 2xl:mt-30">
    <div class="container">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-30px text-primary-color dark:text-body-color">
        <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
          <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
            <span class="purecounter !font-sora tracking-[0.04em]" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2">0</span>

          </div>
          <div class="text">Years of <br />Experience</div>
        </div>

        <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
          <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
            <span class="purecounter !font-sora tracking-[0.04em]" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2">0</span>+

          </div>
          <div class="text">Project <br />Completed</div>
        </div>

        <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
          <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
            <span class="purecounter !font-sora tracking-[0.04em]" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2">0</span>K

          </div>
          <div class="text">Happy <br />Clients</div>
        </div>

        <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
          <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
            <span class="purecounter !font-sora tracking-[0.04em]" data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2">0</span>

          </div>
          <div class="text">Years of <br />Experience</div>
        </div>
      </div>
    </div>
  </div>

</section>
@endif