<div class="{{ $styles['searchNoResults'] }} ">
    {{ $noResultsMessage }}
</div>
<input type="hidden" wire:model.live="description" value="{{ $description }}" name="{{$name . '_name'}}">

