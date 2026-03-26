<a href="{{$url}}" class="w-full">
  <div class="gallery_item">
    <img class="h-[300px]" src="{{ $src }}" alt="{{ $alt ?? '' }}" title="{{ $title ?? '' }}" height="300" />
    <span href="{{$url}}" title="{{ $title }}" class="text-primary-color-light dark:text-white-color hover:text-secondary-color">{{$title}}</span>
  </div>
</a>
