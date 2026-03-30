{{-- Login page background slideshow --}}
@php
    $images = \Taba\Crm\Models\CrmSetting::get(
        'crm_login_slideshow_images',
        config('crm.login.slideshow_images', [])
    );

    // Normalize: repeater stores [{url: '...'}, ...], config stores plain strings
    $urls = collect(is_array($images) ? $images : [])
        ->map(fn ($item) => is_array($item) ? ($item['url'] ?? null) : $item)
        ->filter()
        ->values()
        ->toArray();

    $interval = (int) \Taba\Crm\Models\CrmSetting::get(
        'crm_login_slideshow_interval',
        config('crm.login.slideshow_interval', 6)
    );

    $opacity = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageOpacity() ?? '70%';
@endphp

@if(count($urls))
<div
    x-data="{
        images: @js($urls),
        current: 0,
        interval: {{ $interval * 1000 }},
        init() {
            if (this.images.length > 1) {
                setInterval(() => {
                    this.current = (this.current + 1) % this.images.length;
                }, this.interval);
            }
        }
    }"
    class="absolute inset-0 h-full w-full overflow-hidden"
>
    <template x-for="(img, index) in images" :key="index">
        <div
            class="absolute inset-0 h-full w-full bg-cover bg-center transition-opacity duration-1000 ease-in-out"
            :style="`background-image: url('${img}'); opacity: ${current === index ? '{{ $opacity }}' : '0'};`"
        ></div>
    </template>
</div>
@endif
