<x-layouts.main :title="$page->title">

  <crm::x-banner :title="$page->title" backgroundImage="{{ $page->image->url ?? asset('/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">

    <crm::x-breadcrumbs :items="[
          ['label' => 'الرئيسية', 'url' => route('home')],
          ['label' => __($page->slug), 'url' => route('dynamic.route', ['slug' => $page->slug])],]" />

  </crm::x-banner>

  <x-container>
    <div class="prose mt-8 mx-auto">
      {!! tiptap_converter()->asHTML($page->content,toc:true,maxDepth:3) !!}
    </div>
  </x-container>
</x-layouts.main>
