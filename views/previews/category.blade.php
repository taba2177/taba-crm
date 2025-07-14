<x-layouts.preview>
  @if(view()->exists($componentView))

  {{-- Check if the component exists and render it --}}
  @component($componentView, ['posts' => $posts])@endcomponent

  @else
  <x-templates.default :posts="$posts" />
  @endif
</x-layouts.preview>