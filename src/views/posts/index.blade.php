@foreach($categories as $i => $cat)
    @if($cat->slug === $category->slug)
        @php
            $componentPath = null;
            if (!empty($posts) && isset($posts[0])) {
                $post = $posts[0];
                // Prioritize post-specific component
                if (!empty($post->homepage_section_component) && view()->exists("components.homepage.{$post->homepage_section_component}")) {
                    $componentPath = "components.homepage.{$post->homepage_section_component}";
                }
                // Fallback to category-specific component if post-specific doesn't exist
                elseif (!empty($post->postCategory->section_component) && view()->exists("components.homepage.{$post->postCategory->section_component}")) {
                    $componentPath = "components.homepage.{$post->postCategory->section_component}";
                }
            }
        @endphp

        @if($componentPath)
        <x-layouts.main>
          <x-banner :title="$posts->first()->postCategory->name" :post="$posts->first()" backgroundImage="{{  $posts->first()->getRandomImage() }}">
            <x-breadcrumbs :items="[
                      ['label' => 'الرئيسية', 'url' => route('home')],
                      ['label' => $posts->first()->postCategory->name, 'url' => route('dynamic.route', [$posts->first()->postCategory->slug])],
                      ['label' => $posts->first()->postCategory->description, 'url' => '']]" />

          </x-banner>

            @component($componentPath, ['posts' => $posts]) @endcomponent
        </x-layouts.main>

        @else
            <x-templates.default :posts="$posts" />
        @endif
    @endif
@endforeach


{{-- Check if a specific template exists for the category --}}
{{-- 
@if(view()->exists("components.templates.$i"))
@component("components.templates.{$i}", ['posts' => $posts])@endcomponent
@else
<x-templates.default :posts="$posts" />
@endif
@endif --}}


{{-- If no category matched, show default --}}
{{-- @if(!$loop->parent->break ?? false)
<x-templates.default :posts="$posts" />
@endif --}}