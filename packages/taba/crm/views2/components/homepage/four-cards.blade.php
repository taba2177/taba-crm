@props(['posts'])
@if(!empty($posts))
<section id="services">
    <div class="relative isolate overflow-hidden bg-gray-900 py-24 sm:py-32">
        <!-- Background gradient effects -->
        <div class="hidden sm:absolute sm:-top-10 sm:right-1/2 sm:-z-10 sm:mr-10 sm:block sm:transform-gpu sm:blur-3xl"
            aria-hidden="true">
            <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="absolute -top-52 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:top-[-28rem] sm:ml-16 sm:translate-x-0 sm:transform-gpu"
            aria-hidden="true">
            <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Section heading -->
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                    {{ $posts->first()->postCategory->name }}</h2>
                <p class="mt-6 text-lg leading-8 text-gray-300">{{ $posts->first()->postCategory->description }}
                </p>
            </div>
            <!-- Services grid -->
            <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                @foreach($posts as $index => $post)
                @php
                $delays = [0.3, 0.5, 0.7, 0.9];
                $delay = $delays[$index % 4];
                @endphp
                <div
                    class="flex flex-col rounded-2xl bg-gray-800 p-8 ring-1 ring-gray-700/10 transition-all duration-300 hover:ring-2 hover:ring-indigo-500">
                    <div class="mb-6">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-600">
                            @if(!empty($post->icon))
                            <i class="{{ $post->icon }} text-2xl text-white"></i>
                            @else
                            <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}"
                                alt="{{ $post->title ?? '' }}" class="h-8 w-8 object-contain" />
                            @endif
                        </span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold leading-7 text-white">{{ $post->title ?? '' }}</h3>
                        <div class="mt-3 text-base leading-7 text-gray-300">
                            @if(!empty($post->excerpt))
                            {{ $post->excerpt }}
                            @else
                            <div class="prose prose-invert max-w-none">
                                @foreach ($post->blocks as $block)
                                @switch($block->type)
                                @case('markdown')
                                @markdom($block->data->content)
                                @break
                                @case('figure')
                                <crm::x-figure :image="$block->data->image" :alt="$block->data->alt"
                                    :caption="$block->data->caption" />
                                @break
                                @default
                                @dump($block)
                                @endswitch
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <a class="absolute inset-0 z-10" href="#"></a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif