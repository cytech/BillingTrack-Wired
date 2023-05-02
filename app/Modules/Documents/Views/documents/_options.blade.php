<div class="mb-3">
    {!! Form::hidden('next_date', null, ['id' => 'next_date']) !!}
    {!! Form::hidden('stop_date', null, ['id' => 'stop_date']) !!}
    {!! Form::hidden('recurring_frequency', null, ['id' => 'recurring_frequency']) !!}
    {!! Form::hidden('recurring_period', null, ['id' => 'recurring_period']) !!}
    {!! Form::hidden('group_id', $document->group_id , ['id' => 'group_id']) !!}
    <label>@lang('bt.'.$document->lower_case_baseclass) #</label>
    {!! Form::text('number', $document->number, ['id' => 'number', 'class' =>
    'form-control
    form-control-sm']) !!}
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
        {!! Form::text('discount', $document->formatted_numeric_discount, ['id' =>
        'discount', 'class' => 'form-control form-control-sm']) !!}
        <span class="input-group-text">%</span>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.currency')</label>
    {!! Form::select('currency_code', $currencies, $document->currency_code, ['id' =>
    'currency_code', 'class' => 'form-select form-select-sm']) !!}
</div>
<div class="mb-3">
    <label>@lang('bt.exchange_rate')</label>
    <div class="input-group">
        {!! Form::text('exchange_rate', $document->exchange_rate, ['id' =>
        'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
        <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                data-toggle="tooltip" data-placement="left"
                title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
        </button>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.status')</label>
    {!! Form::select('document_status_id', $statuses, $document->document_status_id,
    ['id' => 'document_status_id', 'class' => 'form-select form-select-sm']) !!}
</div>
<div class="mb-3">
    <label>@lang('bt.template')</label>
    {!! Form::select('template', $templates, $document->template,
    ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
</div>
