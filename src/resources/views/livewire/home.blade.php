<div>
  @foreach($sections as $section)
  @if($section->section_component && view()->exists('components.homepage.' . $section->section_component))
  <x-dynamic-component :component="'homepage.' . $section->section_component" :posts="$section->posts" />
  @endif
  @endforeach
</div>