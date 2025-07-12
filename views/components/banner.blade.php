{{-- @props(['post' => //the post may by null so we need to create empty instans from Post model
'\App\Models\Post',
'backgroundImage' => '/assets/img/breadcrumb/breadcrumb-bg.jpg']) --}}
<section class="{{ $class ?? '' }}">
  <div class="hero-breadcurmb pt-150px md:pt-40 lg:pt-200px pb-50px md:pb-60px lg:pb-100px bg-cover bg-center bg-no-repeat relative z-1 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:bg-primary-color-light after:-z-1 after:opacity-70" style="background-image: url('{{ $backgroundImage ?? $post->getRandomImage() }}')">

    <div class="container">
      <div class="flex flex-col items-center">
        <h1 class="text-size-35 md:text-size-40 lg:text-size-50 font-bold text-white-color mb-15px">
          {{ $title }}
        </h1>
        {{ $slot }}
        @isset($post)
        @if($post->postCategory->slug === 'blogs')
        <div class="text-white mt-3">
          نشر بواسطة {{ $post->user->name }} بتاريخ
          <time datetime="{{ $post->published_at->format('Y-m-d H:i:s') }}">
            {{ $post->published_at->translatedFormat('j F، Y', 'ar') }}
          </time>
        </div>
        @endif
        @endisset
      </div>
    </div>
  </div>
</section>
