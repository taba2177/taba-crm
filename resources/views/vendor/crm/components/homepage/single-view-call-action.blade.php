@props(['posts'])
@if(!empty($posts))
<section class="hero-breadcurmb py-20 md:py-50px xl:py-30 bg-cover bg-center bg-no-repeat relative z-1 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:bg-primary-color-light after:-z-1 after:opacity-70" style="background-image: url('{{ $backgroundImage ?? $posts->first()->getRandomImage() }}')">
  <div class="container">
    @foreach($posts as $post)
    <div class="flex flex-col items-center py-75px md:py-20 lg:py-95px px-15px lg:px-25px rounded-25px text-center text-center p-12 rounded-3xl glass-effect pulse-glow">
      <div class="w-98px h-98px mb-10px wow fadeInUp" data-wow-delay=".3s">
        @if($post->icon ?? false)
        <img src="{{ asset($post->icon) }}" class="h-full object-contain rounded-100%" alt="">
        @else
        {{-- <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" class="h-full object-contain rounded-100%"
          alt=""> --}}
          {{-- <x-section-header-dark title="{{ $posts->first()->postCategory->name ?? '' }}" description="{{ $posts->first()->postCategory->description ?? '' }}" /> --}}
        @endif
      </div>
      <h2
        class="text-size-35 sm:text-size-40 md:text-size-45 lg:text-size-50 xl:text-size-58 inline-block text-white-color leading-1.2 font-medium mb-25px">
        {{ $post->postCategory->name ?? 'If you have any project in mind' }}
      </h2>
      <p class="text-white-color font-medium mb-25px wow fadeInUp" data-wow-delay=".4s">
        {{ $post->postCategory->description ?? 'I am here to help you with your project.' }}
      </p>
      <div class="wow fadeInUp" data-wow-delay=".5s">
        <a href="#"
          class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
          {{ __('make call') }}
        </a>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif