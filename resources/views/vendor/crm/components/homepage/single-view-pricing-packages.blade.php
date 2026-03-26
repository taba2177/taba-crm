
@props(['posts'])
@if(!empty($posts))
<section id="pricing-packages" class="py-60px md:py-20 lg:py-30">
    <div class="container">
        <x-section-header title="Pricing Packages" description="Find the perfect plan for your needs."/>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-30px">
            @foreach($posts as $post)
            <div class="wow fadeInUp" data-wow-delay=".3s">
                <div class="p-30px border border-body-color dark:border-bg-color-2 rounded-15px">
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
                    <a href="#" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group">
                        {{ __('Choose Plan') }} <i class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
