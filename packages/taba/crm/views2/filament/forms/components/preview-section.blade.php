<div>
    @if($component)
        <x-dynamic-component
            :component="$component"
            :posts="$posts"
        />
    @else
        <div class="text-center">
            <p>Select a component to see the preview.</p>
        </div>
    @endif
</div>
