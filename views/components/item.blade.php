@props([
'href' => null,
'active' => false,
])

<li class="nav_item group relative">
    @if($href)
    <a href="{{ $href }}" class="font-medium text-white-color capitalize relative z-0 after:w-0 after:h-[1px] after:bg-white-color after:absolute after:left-0 after:bottom-0 after:transition-all after:duration-500 group-hover:after:w-full">
        {{ $slot }}
    </a>
    @else
    <p class="font-medium text-white-color capitalize relative flex items-center gap-[10px]">
        <i class="far fa-long-arrow-right"></i>
        {{ $slot }}
    </p>
    @endif
</li>
