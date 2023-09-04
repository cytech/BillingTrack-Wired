<div id="laravel-livewire-modals" tabindex="-1"
     {{--    data-bs-backdrop="static" data-bs-keyboard="false"--}}
     data-bs-backdrop="static"
     wire:ignore.self
     class="modal fade">
    <div class="modal-dialog {{ $classes }}">
        <div class="modal-content">
            @if($alias)
                @livewire($alias, $params, key($alias))
            @endif
        </div>
    </div>
</div>
