<div class="position-relative">
    <input
            type="text"
            class="form-control"
            placeholder="Search Vendors..."
            wire:model="query"
            wire:keydown.escape="resetFilters"
            wire:keydown.tab="resetFilters"
            wire:keydown.ArrowUp="decrementHighlight"
            wire:keydown.ArrowDown="incrementHighlight"
            wire:keydown.enter="selectVendor"
    />
    <div wire:loading class="position-absolute list-group bg-white">
        <div class="list-item">Searching...</div>
    </div>
    @if(!empty($query))
        <div class="fixed" wire:click="resetFilters"></div>
        <div class="position-absolute list-group bg-white" style="position: absolute; z-index: 999;">
            @if(!empty($vendors))
                @foreach($vendors as $i => $vendor)
                    <a href="#"
                       class="list-group-item list-group-item-action {{ $highlightIndex === $i ? 'highlight' : '' }}"
                       </a>
                @endforeach
            @else
                <div class="list-group-item">No results!</div>
            @endif
        </div>
    @endif
</div>
