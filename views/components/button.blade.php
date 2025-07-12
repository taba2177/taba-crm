<a href="{{ $href }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300 whitespace-nowrap group/nested {{ $class ?? '' }}">
    {{ $label }}
    @if(isset($icon))
    <i class="fal {{ $icon }} ml-10px -rotate-45 group-hover/nested:rotate-0 transition-all duration-300"></i>
    @endif
</a>
