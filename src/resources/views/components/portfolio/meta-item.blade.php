@props(['label', 'value'])
@php
    $label = $label ?? '';
    $value = $value ?? '';
@endphp
<li>
    <p class="text-primary-color-light dark:text-white-color mb-1.5">
        {{ $label }}
    </p>
    <p class="text-primary-color-light dark:text-white-color font-medium mb-1.5">
        {{ $value }}
    </p>
</li>
