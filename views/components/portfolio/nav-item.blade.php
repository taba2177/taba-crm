<a href="{{ $href }}" class="flex gap-5 items-end group/nested {{ $direction === 'next' ? 'ml-auto' : '' }}">
    @if($direction === 'prev')
    <span class="text-xl md:text-3xl text-white rotate-45 group-hover/nested:rotate-0 transition-all duration-500">
        <i class="fal fa-arrow-left mb-2"></i>
    </span>
    @endif

    <h6>
        <span class="text-white-color block">
            {{ $direction === 'prev' ? 'Previous Project' : 'Next Project' }}
        </span>
        <span class="text-white-color capitalize relative z-0 text-size-25 md:text-size-35 lg:text-size-45 font-bold">
            {{ $projectName }}
        </span>
    </h6>

    @if($direction === 'next')
    <span class="text-xl md:text-3xl text-white -rotate-45 group-hover/nested:rotate-0 transition-all duration-500">
        <i class="fal fa-arrow-right mb-2"></i>
    </span>
    @endif
</a>
