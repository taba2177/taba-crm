@props(['posts'])
@if(!empty($posts))
<section id="vision-mission" class="py-60px md:py-20 lg:py-30">
  <div class="container">
    <x-section-header title="{{ $posts->first()->postCategory->name ?? '' }}"
      description="{{ $posts->first()->postCategory->description ?? '' }}" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-30px">
      @foreach($posts as $post)
      <div class="wow fadeInUp shadow-lg rounded p-4" data-wow-delay=".3s">
        <h3 class="text-size-22 md:text-size-25 lg:text-3xl font-bold mb-15px">{{ $post->title }}</h3>
        <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2 ">
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
      @endforeach
    </div>
  </div>
</section>
@endif