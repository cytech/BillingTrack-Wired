<div class="modal-dialog modal-lg" id="create-seeded-workorder-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"
                id="createModuleModalLabel">
                @lang('bt.create_workorder')
                for @lang('bt.job_date') {{ \Carbon\Carbon::parse($job_date)->format('l F j, Y') }}</h4>
            <button class="btn-close"
                    type="button"
                    aria-label="Close"
                    wire:click.prevent="doCancel()">
            </button>
        </div>
        <div class="modal-body">
            <form autocomplete="off">
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label for="company_profile_id" class="col-form-label">@lang('bt.company_profile')</label>
                    </div>
                    <div class="col-sm-7">
                        {!! Form::select('company_profile_id', $companyProfiles, config('bt.defaultCompanyProfile'),
                        ['id' => 'company_profile_id', 'class' => 'form-select']) !!}
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label for="resource_name" class="col-form-label">@lang('bt.customer')</label>
                    </div>
                    <div class="col-sm-7">
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
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label for="summary" class="col-form-label">@lang('bt.job_summary')</label>
                    </div>
                    <div class="col-sm-7">
                        <input wire:model.defer="summary" type="text" id="summary" name="summary" class="form-control"
                               placeholder="Enter Job Summary - (500 characters max)">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-2 text-end fw-bold">
                        <label for="start_time"
                               class="col-form-label">@lang('bt.start_time')</label>
                    </div>
                    <div class="col-sm-2">
                        <x-fp_time
                                name="start_time"
                                id="start_time"
                                class="form-control"
                                value="08:00"
                                wire:model="start_time"
                        ></x-fp_time>
                        @error('start_time') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-sm-2 text-end fw-bold">
                        <label for="end_time" class="col-form-label">@lang('bt.end_time')</label>
                    </div>
                    <div class="col-sm-2">
                        <x-fp_time
                                name="end_time"
                                id="end_time"
                                class="form-control"
                                value="09:00"
                                wire:model="end_time"
                        ></x-fp_time>
                        @error('end_time') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-sm-2 text-end fw-bold">
                        <label class="form-check-label" for="will_call">@lang('bt.will_call')</label>
                    </div>
                    <div class="col-sm-2 form-check form-switch form-switch-md">
                        {!! Form::checkbox('will_call', 1, null, ['wire:model.defer' => 'will_call', 'id' => 'will_call', 'class' => 'form-check-input']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 ms-5">
                        <br>
                        <b>@lang('bt.available_employees')</b><br>
                        @lang('bt.select_workers_toworkorder')<br>
                        <div class="form-check" id="ScrollCB1" style="max-height:200px;overflow:auto;padding-left:1.75em">
                            @foreach($available_employees as $key => $value)
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="workers[]"
                                       id="workers{{$value->id}}"
                                       wire:model.defer="selected_employees"
                                       value="{{$value->id}}"
                                       style="transform: scale(1.3);"
                                />
                                @if($value->driver)
                                    <label class="form-check-label mb-1" style="display:block;color:blue;">
                                @else
                                    <label class="form-check-label mb-1" style="display:block;">
                                @endif
                                    {{$value->short_name}}
                                    </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <br>
                        <b>@lang('bt.available_equip')</b><br>
                        @lang('bt.select_items_toworkorder')
                        <div class="form-check" id="ScrollCB2" style="max-height:200px;overflow:auto;padding-left:1.75em">
                            @foreach($available_resources as $key => $value)
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="resources[]"
                                       id="resources{{$value->id}}"
                                       wire:model="selected_resources.{{$key}}"
                                       value="{{$value->id}}"
                                       style="transform: scale(1.3);"
                                />
                                <label class="mb-1" style="display:block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                    {{ $value->name }}
                                    <input
                                            type="number"
                                            name="quantity[{{$value->id}}]"
                                            wire:model.defer="selected_qty.{{$value->id}}"
                                            min="0"
                                            style="width:40px;height:25px;margin-left:10px;margin-right:5px;"
                                            {{ !in_array($value->id, $selected_resources) ? 'disabled' : '' }}
                                            value="0"
                                    />
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary"
                            wire:click.prevent="createModule()">@lang('bt.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    // set quantity to 0 if resource is checked/unchecked
    let modal_id = document.getElementById('create-seeded-workorder-modal')
    let wire_id = window.livewire.find(modal_id.getAttribute("wire:id"))
    modal_id.querySelectorAll("input[name^=resources]").forEach(res => {
        res.addEventListener("click", () => {
            wire_id.set('selected_qty.' + res.value, 1)
        })
    })
</script>
