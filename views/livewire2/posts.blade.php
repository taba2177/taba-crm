@if(request()->is('*blogs*'))
<section class="wide-tb-100 bg-light-theme bg-gradiant">
  <div class="container">
    <div class="row">
      <!-- Heading Main -->
      <div class="wow fadeInDown text-center" data-wow-duration="0" data-wow-delay="0s">
        <h2 class="heading-main">
          <span>احدث المقالات</span></h2>
        <h3 class="m-4 fw-5 txt-orange">تعرف على كل ما يخص الستائر</h3>
      </div>
      <!-- Heading Main -->
      @foreach ($posts as $post)
      <div class="col-sm-12 col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
        <div class="blog-warp">
          {{-- <a href="{{ $post->url }}" class="group"> --}}
          <!-- Blog Image -->
          {{-- <div class="blog-image">
                  <img
                    src="{{ $post->image?->url ?? $post->getRandomImage() ?? '/assets/images/blog_img_1.jpg' }}"
          alt="{{ $post->image->alt ?? $post->title }}"
          class=""
          />
        </div> --}}
        {{-- <div> --}}
        {{-- <div class="blog-warp"> --}}
        {{-- <img src={{ asset('storage/'.$post->user->avatar_url)}} alt="" class="rounded"> --}}
        <a href="{{$post->url}}" title="{{ $post->title }}">
          <img src="{{ $post->image?->url ?? $post->getRandomImage() ?? '/assets/images/blog_img_1.jpg' }}" alt="{{ $post->image->alt ?? $post->title }}" class="bordered-img" width="400" height="400" loading="lazy" />
        </a>
        <div class="meta-box">

          <a href="{{$post->url}}" title="{{$post->title}}">
            {{-- {{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }} --}}

            {{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }}
          </a>
          <span>/</span>
          {{ $post->published_at->translatedFormat('j F، Y', 'ar') }}
        </div>
        <h4 class="h4-md mb-3"><a href="{{$post->url}}" title="{{$post->title}}">{{$post->title}}</a></h4>
        <p>{{ $post->excerpt }}</p>
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </a> --}}
      </div>
    </div>
    @endforeach
  </div>
  <!-- Blog Items -->

  <!-- Show All Posts Button -->
  <div class="row mt-5">
    <div class="col-sm-12 text-center wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s">
      <a href="{{ route('posts') }}" titlle="عرض جميع المقالات" class="btn-theme icon-left bg-orange no-shadow">
        قراءة المزيد
      </a>
    </div>
  </div>
  </div>
</section>
@endif
