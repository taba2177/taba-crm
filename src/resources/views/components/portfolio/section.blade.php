<div class="mb-10 md:mb-50px {{ $class ?? '' }}">
    <div>
        @if(isset($title))
        <h3 class="mb-15px md:mb-5">
            <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                {{ $title }}
            </span>
        </h3>
        @endif
        {{ $slot }}
    </div>
</div>
