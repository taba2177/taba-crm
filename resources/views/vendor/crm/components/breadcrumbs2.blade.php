@props(['image' => null, 'post' => null, 'title' => null, 'items' => []])
{{-- The main hero container div --}}
<section id="{{ $post?->postCategory->slug }}" style="background-image: url({{ $image ?? $post?->getRandomImage() }});"
    class="relative bg-cover bg-center bg-no-repeat pb-[50px] pt-40 md:pb-[60px] md:pt-60 lg:pb-[100px] lg:pt-[200px]
           after:absolute after:inset-0 after:bg-black/60 after:backdrop-blur-sm">
    {{-- HIGHLIGHT: Changed overlay to bg-black/60 and added backdrop-blur-sm --}}

    <div class="container relative z-10"> {{-- HIGHLIGHT: Added relative and z-10 to lift content above overlay --}}
        <div class="flex flex-col items-center px-5 py-12">
            <ul class="mx-4 mb-2 flex items-center gap-x-2.5 md:mx-auto">
                @foreach($items as $item)
                @if(!$loop->first)
                {{-- This is the separator icon --}}
                <li>
                    <span class="relative flex items-center gap-2.5 font-medium capitalize text-white">
                        {{-- LTR Separator --}}
                        <svg class="h-5 w-5 text-gray-300 rtl:hidden" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{-- RTL Separator --}}
                        <svg class="hidden h-5 w-5 text-gray-300 ltr:hidden rtl:flex" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </li>
                @endif

                {{-- A breadcrumb item --}}
                <li class="group relative max-w-[150px] overflow-hidden md:max-w-full">
                    @if(!$loop->last)
                    <a href="{{ $item['url'] }}"
                        class="relative z-0 font-medium capitalize text-white after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-white after:transition-all after:duration-500 group-hover:after:w-full">
                        {{ $item['label'] }}
                    </a>
                    @else
                    <p
                        class="relative flex animate-marquee-ltr items-center gap-2.5 whitespace-nowrap font-medium capitalize text-white rtl:animate-marquee-rtl md:animate-none md:truncate">
                        {!! $title ?? str_replace('-', '', $item['label'] ?? '') !!}
                    </p>
                    @endif
                </li>
                @endforeach
            </ul>
            <h2 class="mb-4 text-center text-[35px] font-bold text-white md:text-3xl lg:text-[50px]"
                style="line-height: 4.25rem;">
                {!! $title ?? str_replace('-', '<br>', $post->title ?? '') !!}
            </h2>
        </div>
    </div>
</section>