@php $matched = false; @endphp
@foreach($categories as $i => $cat)
    @if(isset($category) && $cat->slug === $category->slug)
    @php $matched = true; @endphp
    @foreach($posts as $j => $post)
            @if($post->post_category_id === $cat->id && view()->exists("components.homepage.$post->homepage_section_component"))
                @component("components.homepage.$post->homepage_section_component", ['posts' => $posts])@endcomponent
                @elseif($post->post_category_id === $cat->id && view()->exists("components.homepage.".$post->postCategory->section_component))
                <x-layouts.main>
                  <x-banner :title="$posts->first()->postCategory->name" :post="$posts->first()" backgroundImage="{{  $posts->first()->getRandomImage() }}">
                    <x-breadcrumbs :items="[
                            ['label' => 'الرئيسية', 'url' => route('home')],
                            ['label' => $posts->first()->postCategory->name, 'url' => route('dynamic.route', [$posts->first()->postCategory->slug])],
                            ['label' => $posts->first()->postCategory->description, 'url' => '']
                        ]" />
                  </x-banner>
                  @component("components.homepage.".$post->postCategory->section_component, ['posts' => $posts])@endcomponent
                </x-layouts.main>
                @break
            @endif
        @endforeach
    @elseif(view()->exists("components.templates.$i"))
    @component("components.templates.{$i}", ['posts' => $posts])@endcomponent
        @php $matched = true; @endphp
        @endif
@endforeach
@if(!$matched)
<x-templates.default :posts="$posts" />
@endif

