<div>
    <div class="modal-header">
        <h4 class="modal-title">
            @if($module)
                @lang('bt.enter_payment') : @lang('bt.' . strtolower($module->module_type)) #{{ $module['number'] }}
            @else
                @lang('bt.enter_client_payment')
            @endif</h4>
        <button type="button" class="btn-close" wire:click.prevent="doCancel()" aria-hidden="true"></button>
    </div>
    <div class="modal-body">
        <div id="modal-status-placeholder"></div>
        <form>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
            @if(!$readonly)
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.client')</label></div>
                    <div class="col-sm-7">
                        <livewire:client-search
                                {{-- module base name, adds hidden fields with _id and _name --}}
                                wire:onload="$dispatch('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);"
                                name="client"
                                :value="$resource_id"
                                :description="$resource_name"
                                placeholder="{{ __('bt.select_or_create_client') }}"
                                :searchable="true"
                                noResultsMessage="{{__('bt.client_not_found_create')}}"
                                :readonly="$readonly"
                        />
                        @error('resource_id') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-4 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.invoice')</label></div>
                    <div class="col-sm-7">
                        <select
                                name="client_invoices"
                                id="client_invoices"
                                class="form-select client_invoices"
                                wire:model.live="invoice_id">
                            <option value="">
                                @lang('bt.select_invoice')
                            </option>
                            @foreach($client_invoices as $invoice)
                                <option value="{{ $invoice->id }}">
                                    {{ $invoice->number . ' - ' . __('bt.due') .': ' . $invoice->formatted_due_at . ' - ' . __('bt.balance') . $invoice->amount->formatted_numeric_balance }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.amount')</label></div>
                <div class="col-sm-7">
                    {{ html()->text('payment_amount', null)->attribute('wire:model.live', 'amount')->placeholder('xxx.xx')->class('form-control') }}
                    @error('amount') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.payment_date')</label></div>
                <div class="col-sm-7">
                    <x-fp_common
                            name="payment_date"
                            id="payment_date"
                            class="form-control"
                            :value="$paymentdate"
                            wire:model.live="paymentdate"
                    ></x-fp_common>
                    @error('paymentdate') <span class="text-sm text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.payment_method')</label></div>
                <div class="col-sm-7">
                    {{ html()->select('payment_method_id', $paymentmethods, null)->attribute('wire:model.live', 'payment_method_id')->class('form-select') }}
                </div>
            </div>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-sm-4 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.note')</label></div>
                <div class="col-sm-7">
                    {{ html()->textarea('payment_note', null)->rows(4)->attribute('wire:model.live', 'payment_note')->class('form-control') }}
                </div>
            </div>
            {{--@if (config('bt.mailConfigured') and $client->email)--}}
            @if (config('bt.mailConfigured'))
                <hr>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-7 text-end fw-bold">
                        <label class="form-check-label">@lang('bt.email_payment_receipt')</label></div>
                    <div class="form-check form-switch form-switch-md col-sm-4 ms-2">
                        {{ html()->checkbox('email_payment_receipt', (bool)$email_payment_receipt, 1)->attribute('wire:model.live', 'email_payment_receipt')->class('form-check-input') }}
                    </div>
                </div>
            @endif
            <div id="payment-custom-fields">
                @if ($customFields->count())
                    @foreach ($customFields as $key => $value)
                        <div class="row g-3 mb-3 align-items-center">
                            <div class="col-sm-4 text-end fw-bold">
                                <label class="col-form-label">{{ $value->field_label }}</label></div>
                            <div class="col-sm-7">
                                @if ($value->field_type == 'dropdown')
                                    {{--                                        {!! Form::select('custom[' . $value->column_name . ']', array_combine(array_merge([''], explode(',', $value->field_meta)), array_merge([''], explode(',', $value->field_meta))), null, ['wire:model.live' => 'custom_data.' . $value->column_name .'', 'class' => 'custom-form-field form-select', 'data-' . $value->tbl_name . '-field-name' => $value->column_name]) !!}--}}
                                    {{ html()->select('custom[' . $value->column_name . ']', array_combine(array_merge([''], explode(',', $value->field_meta)), array_merge([''], explode(',', $value->field_meta))), (isset($object->custom->{$value->column_name}) ? $object->custom->{$value->column_name} : null))->attribute('wire:model.live', 'custom_data.' . $value->column_name .'')->class(['custom-form-field form-select'])->attribute('data-' . $value->tbl_name . '-field-name', $value->column_name)}}
                                @else
                                    {{--                                        {!! call_user_func_array('Form::' . $value->field_type, ['custom[' . $value->column_name . ']', null, ['wire:model.live' => 'custom_data.' . $value->column_name .'','class' => 'custom-form-field form-control', 'data-' . $value->tbl_name . '-field-name' => $value->column_name]]) !!}--}}
                                    {{ html()->{$value->field_type}('custom[' . $value->column_name . ']', (isset($object->custom->{$value->column_name}) ? $object->custom->{$value->column_name} : null))->attribute('wire:model.live', 'custom_data.' . $value->column_name .'')->class(['custom-form-field form-control'])->attribute('data-' . $value->tbl_name . '-field-name', $value->column_name)}}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()">
            @lang('bt.cancel')
        </button>
        <button type="button" class="btn btn-primary"
                wire:click.prevent="createPayment()">@lang('bt.submit')</button>
    </div>
</div>
