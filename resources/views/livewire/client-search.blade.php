<div class="position-relative">
    <input
            type="text"
            class="form-control"
            placeholder="Search Clients..."
            wire:model="query"
            wire:keydown.escape="resetFilters"
            wire:keydown.tab="resetFilters"
            wire:keydown.ArrowUp="decrementHighlight"
            wire:keydown.ArrowDown="incrementHighlight"
            wire:keydown.enter="selectClient"
    />
    <div wire:loading class="position-absolute list-group bg-white">
        <div class="list-item">Searching...</div>
    </div>
    @if(!empty($query))
        <div class="fixed" wire:click="resetFilters"></div>
        <div class="position-absolute list-group bg-white" style="position: absolute; z-index: 999;">
            @if(!empty($clients))
                @foreach($clients as $i => $client)
                    <a href="#"
                       class="list-group-item list-group-item-action {{ $highlightIndex === $i ? 'highlight' : '' }}"
                       title="{{$client['unique_name']}}">{{ $client['name'] }}</a>
                @endforeach
            @else
                <div class="list-group-item">No results!</div>
            @endif
        </div>
    @endif
</div>
