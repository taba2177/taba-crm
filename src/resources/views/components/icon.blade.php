@if($type === 'arrow')
<i class="fal fa-arrow-{{ $direction }} {{ $class ?? '' }}"></i>
@elseif($type === 'long-arrow')
<i class="far fa-long-arrow-{{ $direction }} {{ $class ?? '' }}"></i>
@endif
