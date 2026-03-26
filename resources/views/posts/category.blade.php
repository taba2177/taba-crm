@foreach($categories as $i =>$cat )

@if($cat->slug === $category->slug)

@if(view()->exists("components.templates.$i"))

@component("components.templates.{$i}", ['posts' => $posts])@endcomponent

@else
<x-templates.default :posts="$posts" />
@endif
@endif

@endforeach

{{-- If no category matched, show default --}}
{{-- @if(!$loop->parent->break ?? false)
<x-templates.default :posts="$posts" />
@endif --}}
