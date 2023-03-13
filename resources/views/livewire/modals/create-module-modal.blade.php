<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"
                id="createModuleModalLabel">@lang('bt.'.$moduleop.'_'. strtolower(snake_case($moduletype)))</h4>
            <button class="btn-close"
                    type="button"
                    aria-label="Close"
                    wire:click.prevent="doCancel()">
            </button>
        </div>
        <div class="modal-body">
            <form action="" method="get">
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        @if($moduletype <> 'Purchaseorder')
                        <label class="col-form-label">@lang('bt.client')</label>
                        @else
                        <label class="col-form-label">@lang('bt.vendor')</label>
                        @endif
                    </div>
                    <div class="col-sm-7">
                        @if($moduletype <> 'Purchaseorder')
                            <livewire:client-search
                                    {{-- module base name, adds hidden fields with _id and _name --}}
                                    name="client"
                                    :value="$resource_id"
                                    :description="$resource_name"
                                    placeholder="{{ __('bt.select_or_create_client') }}"
                                    :searchable="true"
                                    noResultsMessage="{{__('bt.client_not_found_create')}}"
                                    :readonly="$readonly"
                            />
                            @error('resource_name') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        @else
                            <livewire:vendor-search
                                    name="vendor"
                                    :value="$resource_id"
                                    :description="$resource_name"
                                    placeholder="{{ __('bt.select_or_create_vendor') }}"
                                    :searchable="true"
                                    noResultsMessage="{{__('bt.vendor_not_found_create')}}"
                                    :readonly="$readonly"
                            />
                            @error('resource_name') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>
                @if($moduletype <> 'RecurringInvoice')
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.date')</label>
                        </div>
                        <div class="col-sm-7">
                            <x-fp_common wire:model.lazy="module_date"/>
                            @error('module_date') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.company_profile')</label>
                    </div>
                    <div class="col-sm-7">
                        {!! Form::select('company_profile_id', $companyProfiles, null,
                        ['wire:model' => 'company_profile_id','id' => 'company_profile_id', 'class' => 'form-select']) !!}
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.group')</label>
                    </div>
                    <div class="col-sm-7">
                        {!! Form::select('group_id', $groups, null,
                        ['wire:model' => 'group_id','id' => 'group_id', 'class' => 'form-select']) !!}
                    </div>
                </div>
                @if($moduletype == 'RecurringInvoice')
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.start_date')</label>
                        </div>
                        <div class="col-sm-7">
                            <x-fp_common
                                    wire:model.lazy="next_date"
                            ></x-fp_common>
                            @error('next_date') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.every')</label>
                        </div>
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::select('recurring_frequency', array_combine(range(1, 90), range(1, 90)), null,
                                                    ['wire:model' => 'recurring_frequency', 'id' => 'recurring_frequency', 'class' => 'form-select']) !!}
                                </div>
                                <div class="col-sm-7">
                                    {!! Form::select('recurring_period', $frequencies, null,
                                                    ['wire:model' => 'recurring_period', 'id' => 'recurring_period', 'class' => 'form-select']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.stop_date')</label>
                        </div>
                        <div class="col-sm-7">
                            <x-fp_common
                                    wire:model.lazy="stop_date"
                            ></x-fp_common>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()">
                Close
            </button>
            <button type="button" class="btn btn-primary"
                    wire:click.prevent="createModule()">@lang('bt.submit')</button>
        </div>
    </div>
</div>
