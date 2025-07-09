
@props(['posts'])
@if(!empty($posts))
<section id="why-choose-us" class="py-60px md:py-20 lg:py-30 bg-primary-color">
    <div class="container">
        <x-section-header-dark title="Why Choose Us" description="Because we are the best at what we do."/>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-30px">
            @foreach($posts as $post)
            <div class="wow fadeInUp" data-wow-delay=".3s">
                <div class="p-30px bg-white dark:bg-dark-color rounded-15px">
                    <h3 class="text-size-22 md:text-size-25 font-bold mb-15px">{{ $post->title }}</h3>
                    <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
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
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
