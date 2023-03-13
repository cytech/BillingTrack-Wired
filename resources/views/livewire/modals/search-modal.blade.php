<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">@lang('bt.change_'.$search_type)</h4>
            <button type="button" class="btn-close" wire:click.prevent="doCancel()" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
            <div id="modal-status-placeholder"></div>
            <form>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
                {{--                @if(!$readonly)--}}
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.client')</label>
                    </div>
                    <div class="col-sm-7">
                        @if($search_type == 'client')
                            <livewire:client-search
                                    {{-- module base name, adds hidden fields with _id and _name --}}
                                    wire:onload="$emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);"
                                    name="client"
                                    :value="$resource_id"
                                    :description="$resource_name"
                                    placeholder="{{ __('bt.select_client') }}"
                                    :searchable="true"
                                    noResultsMessage="{{__('bt.client_not_found')}}"
                                    :readonly="$readonly ?? null"
                            />
                            @error('resource_id') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        @else
                            <livewire:vendor-search
                                    {{-- module base name, adds hidden fields with _id and _name --}}
                                    wire:onload="$emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);"
                                    name="vendor"
                                    :value="$resource_id"
                                    :description="$resource_name"
                                    placeholder="{{ __('bt.select_vendor') }}"
                                    :searchable="true"
                                    noResultsMessage="{{__('bt.vendor_not_found')}}"
                                    :readonly="$readonly ?? null"
                            />
                            @error('resource_id') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>
                {{--                @endif--}}
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()">
                @lang('bt.cancel')
            </button>
            <button type="button" class="btn btn-primary"
                    wire:click.prevent="changeResource()">@lang('bt.submit')</button>
        </div>
    </div>
</div>
