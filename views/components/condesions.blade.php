
    <div class="prose max-w-none">
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

