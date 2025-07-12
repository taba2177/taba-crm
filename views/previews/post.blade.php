<x-layouts.preview>

  @if(view()->exists($componentView))

  @component($componentView, ['post' => $post, 'relatedPosts' => $relatedPosts])@endcomponent

  @else
  <x-templates.default :posts="$post" />
  @endif
</x-layouts.preview>