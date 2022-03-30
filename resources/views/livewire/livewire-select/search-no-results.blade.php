<div class="{{ $styles['searchNoResults'] }} ">
    {{ $noResultsMessage }}
</div>
<input type="hidden" wire:model="description" value="{{ $description }}" name="{{$name . '_name'}}">

