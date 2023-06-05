<div class="mb-3">
    {{ html()->hidden('next_date', null) }}
    {{ html()->hidden('stop_date', null) }}
    {{ html()->hidden('recurring_frequency', null) }}
    {{ html()->hidden('recurring_period', null) }}
    {{ html()->hidden('group_id', $document->group_id) }}
    <label>@lang('bt.'.$document->lower_case_baseclass) #</label>
    {{ html()->text('number', $document->number)->class('form-control form-control-sm') }}
</div>
<div class="mb-3">
    <label>@lang('bt.date')</label>
    <x-fp_common
            id="document_date"
            class="form-control form-control-sm"
            value="{{$document->document_date}}"></x-fp_common>
</div>
<div class="mb-3">
    @if($document->module_type == 'Invoice' || $document->module_type == 'Purchaseorder')
        <label>@lang('bt.due_date')</label>
    @else
        <label>@lang('bt.expires')</label>
    @endif
    <x-fp_common
            id="action_date"
            class="form-control form-control-sm"
            value="{{$document->action_date}}"></x-fp_common>
</div>
<div class="mb-3">
    <label>@lang('bt.discount')</label>
    <div class="input-group input-group-sm">
        {{ html()->text('discount', $document->formatted_numeric_discount)->class('form-control form-control-sm') }}
        <span class="input-group-text">%</span>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.currency')</label>
    {{ html()->select('currency_code', $currencies, $document->currency_code)->class('form-select form-select-sm') }}
</div>
<div class="mb-3">
    <label>@lang('bt.exchange_rate')</label>
    <div class="input-group">
        {{ html()->text('exchange_rate', $document->exchange_rate)->class('form-control form-control-sm') }}
        <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                data-toggle="tooltip" data-placement="left"
                title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
        </button>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.status')</label>
    {{ html()->select('document_status_id', $statuses, $document->document_status_id)->class('form-select form-select-sm') }}
</div>
<div class="mb-3">
    <label>@lang('bt.template')</label>
    {{ html()->select('template', $templates, $document->template)->class('form-select form-select-sm') }}
</div>
