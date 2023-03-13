@if ($this->showBulkActionsDropdown)
    <div class="mb-3 mb-md-0" id="{{ $bulkKey = \Illuminate\Support\Str::random() }}-bulkActionsWrapper">
        <div class="dropdown d-block d-md-inline">
            <button class="btn dropdown-toggle d-block w-100 d-md-inline" type="button" id="{{ $bulkKey }}-bulkActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('bt.bulk_actions')
            </button>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $bulkKey }}-bulkActions">
                @foreach($this->bulkActions as $action => $title)
                    @if($action == 'trash' || $action == 'delete')
                        <hr class="dropdown-divider">
                    @endif
                    <a
                        href="#"
                        wire:click.prevent="{{ $action }}"
                        wire:key="bulk-action-{{ $action }}"
                        class="dropdown-item"
                    >
                        @if($action == 'trash' || $action == 'delete')
                            <i class="fa fa-trash-alt text-danger"></i> {{ $title }}</a>
                        @else
                        <i class="fa fa-exchange-alt"></i> {{ $title }}</a>
                        @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
