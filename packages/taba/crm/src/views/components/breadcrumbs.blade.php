<ul class="nav flex items-center gap-x-10px">
    @foreach($items as $item)
    <li class="nav_item group relative">
        @if(!$loop->last)
        <a href="{{ $item['url'] }}" class="font-medium text-white-color capitalize relative z-0 after:w-0 after:h-1px after:bg-white-color after:absolute after:left-0 after:bottom-0 after:transition-all after:duration-500 group-hover:after:w-full">
            {{ $item['label'] }}
        </a>
        <i class="far fa-long-arrow-left"></i>
        @else
        <p class="font-medium text-white-color capitalize relative flex items-center gap-10px">
            {{ $item['label'] }}
        </p>
        @endif
    </li>
    @endforeach
</ul>
