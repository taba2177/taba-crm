@unless ($breadcrumbs->isEmpty())
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      @foreach ($breadcrumbs as $breadcrumb)
        @if ($breadcrumb->url && ! $loop->last)
          <li class="breadcrumb-item">
            <a
              wire:navigate
              href="{{ $breadcrumb->url }}"
              class="text-white transition-colors hover:text-primary-500 focus:text-primary-500"
            >
              {{ $breadcrumb->title }}
            </a>
          </li>
        @else
          <li class="breadcrumb-item {{ ! $loop->last ? 'text-white' : 'text-primary-500' }}">
            {{ $breadcrumb->title }}
          </li>
        @endif

        @unless ($loop->last)
          <li class="breadcrumb-separator">
            <i class="icofont-rounded-right text-gray-500"></i>
          </li>
        @endif
      @endforeach
    </ol>
  </nav>
@endunless
