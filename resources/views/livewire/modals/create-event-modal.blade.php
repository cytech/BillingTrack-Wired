<div class="modal-dialog ">
    <div class="modal-content">
        <div class="modal-header">
            @if($module)
                <h4 class="modal-title">@lang('bt.update_event')</h4>
            @else
                <h4 class="modal-title">@lang('bt.create_event')</h4>
            @endif
            <button type="button" class="btn-close" wire:click.prevent="doCancel()" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
            <div id="modal-status-placeholder"></div>
            <form id="saveCalendarEvent">
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.title')</label>
                    </div>
                    <div class="col-sm-7">
                        <livewire:employee-search
                                {{-- module base name, adds hidden fields with _id and _name --}}
                                wire:onload="$emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);"
                                name="employee"
                                :value="$resource_id"
                                :description="$resource_name"
                                placeholder="{{ __('bt.placeholder_employee_select') }}"
                                :searchable="true"
                                noResultsMessage="{{  __('bt.no_results_employee') }}"
                        />
                        @error('title') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.location')</label>
                    </div>
                    <div class="col-sm-7">
                        <input wire:model.defer="location" type="text" id="location" name="location"
                               class="form-control" value="">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.description')</label>
                    </div>
                    <div class="col-sm-7">
                        <input wire:model.defer="description" type="text" id="description" name="description"
                               class="form-control" value="">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.category')</label></div>
                    <div class="col-sm-7">
                        {!! Form::select('category_id',$categories,null, ['wire:model' => 'category_id', 'id' => 'category', 'class'=> 'form-select']) !!}
                    </div>
                </div>
                <hr>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.start_datetime')</label>
                    </div>
                    <div class="col-sm-7">
                        <x-fp_datetime
                                name="start_date"
                                id="start_date"
                                class="form-control"
                                style="cursor: pointer"
                                :value="$start_date"
                                wire:model="start_date"
                        ></x-fp_datetime>
                        @error('start_date') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.end_datetime')</label>
                    </div>
                    <div class="col-sm-7">
                        <x-fp_datetime
                                name="end_date"
                                id="end_date"
                                class="form-control"
                                style="cursor: pointer"
                                :value="$end_date"
                                wire:model="end_date"
                        ></x-fp_datetime>
                        @error('end_date') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                {{--                                 <div class="row g-3 mb-3 align-items-center">--}}
                {{--                        <label for="recurring" class="col-sm-4 text-right text">@lang('bt.recurring')</label>--}}
                {{--                        <div class="col-sm-7">--}}
                {{--                            <select id="recurring" class="form-control">--}}
                {{--                                <option selected>Does not repeat</option>--}}
                {{--                                <option value="1">Daily</option>--}}
                {{--                                <option value="2">Weekly</option>--}}
                {{--                                <option value="3">Every Weekday</option>--}}
                {{--                                <option value="4">Bi-Weekly</option>--}}
                {{--                                <option value="5">Monthly</option>--}}
                {{--                                <option value="6">Yearly</option>--}}
                {{--                                <option value="7">Custom</option>--}}
                {{--                            </select>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                <hr>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-auto text-start fw-bold">
                        <label class="col-form-label ps-2">@lang('bt.reminder')</label>
                    </div>
                    <div class="col-sm-2">
                        <input wire:model="reminder_qty" type="number" id="reminder_qty" name="reminder_qty"
                               class="form-control" min="0"
                               value="0"/></div>
                    <div class="col-auto">
                        <select wire:model.defer="reminder_interval_id" id="reminder_interval" name="reminder_interval"
                                class="form-select">
                            @foreach($reminder_interval as $key => $value)
                                <option value="{{$key}}" @if($loop->first) selected @endif>{{$value}}</option>
                            @endforeach
                        </select></div>
                    <div class="col-auto">
                        <label class="fw-bold fs-8">@lang('bt.before_event_start')</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()">
                        @lang('bt.cancel')
                    </button>
                    <button type="button" class="btn btn-primary"
                            wire:click.prevent="createEvent()">@lang('bt.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
