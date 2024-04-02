<div
    class="{{ $styles['searchOptionItem'] }}"

    wire:click.stop="selectValue('{{ $option['value'] }}')"

    @if(array_key_exists('flag_text', $option) && $option['flag_text'] != null)
        x-bind:class="{ '{{ $styles['searchOptionItemActiveFlag'] }}': selectedIndex === {{ $index }}, '{{ $styles['searchOptionItemInactive'] }}': selectedIndex !== {{ $index }} }"
        x-on:mouseover="selectedIndex = {{ $index }}"
        title="{{$option['flag_text']}}"
    @else
        x-bind:class="{ '{{ $styles['searchOptionItemActive'] }}': selectedIndex === {{ $index }}, '{{ $styles['searchOptionItemInactive'] }}': selectedIndex !== {{ $index }} }"
        x-on:mouseover="selectedIndex = {{ $index }}"
        title="{{$option['title']}}"
    @endif
>
    {{ $option['description'] }}
</div>
