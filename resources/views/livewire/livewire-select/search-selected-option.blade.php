<button
        id="{{ $name }}-selected"
        type="button"
        class="{{ $styles['searchSelectedOption'] }}"
        @if(!$readonly)
        x-on:keydown.enter.prevent="removeSelection(@this)"
        x-on:keydown.space.prevent="removeSelection(@this)"
        @else
        readonly
        @endif
>
    <span class="{{ $styles['searchSelectedOptionTitle'] }}">
        {{ mb_strimwidth(data_get($selectedOption, 'description', 'Override selectedOption() with keyed array (value, description) for meaningful description'),0,23, '...') }}
    </span>
    @if(!$readonly)
        <span
                type="text"
                wire:click.prevent="selectValue(null)"
                class="{{ $styles['searchSelectedOptionReset'] }}"
        >
    </span>
    @endif
    <input type="hidden" value="{{ $value }}" name="{{ $name . '_id' }}">
    <input type="hidden" value="{{ $description }}" name="{{$name . '_name'}}">
</button>
