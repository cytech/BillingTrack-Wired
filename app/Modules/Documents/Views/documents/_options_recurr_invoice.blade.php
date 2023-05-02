{!! Form::hidden('number', $document->number , ['id' => 'number']) !!}
{!! Form::hidden('document_date', '0000-00-00', ['id' => 'document_date']) !!}
{!! Form::hidden('action_date', '0000-00-00', ['id' => 'action_date']) !!}
<div class="mb-3">
    <label>@lang('bt.next_date')</label>
    <x-fp_common
            id="next_date"
            class="form-control form-control-sm"
            value="{{$document->next_date}}"
    ></x-fp_common>
</div>
<div class="mb-3">
    <label>@lang('bt.every')</label>
    <div class="row">
        <div class="col-5">
            {!! Form::select('recurring_frequency', array_combine(range(1, 90), range(1, 90)), $document->recurring_frequency, ['id' => 'recurring_frequency', 'class' => 'form-select form-select-sm']) !!}
        </div>
        <div class="col-7">
            {!! Form::select('recurring_period', $frequencies, $document->recurring_period, ['id' => 'recurring_period', 'class' => 'form-select form-select-sm']) !!}
        </div>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.stop_date')</label>
    <x-fp_common
            id="stop_date"
            class="form-control form-control-sm"
            value="{{$document->stop_date == '0000-00-00' ? '' : $document->stop_date}}"
    ></x-fp_common>
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
        {!! Form::text('exchange_rate', $document->exchange_rate, ['id' => 'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
        <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                data-toggle="tooltip" data-placement="left"
                title="@lang('bt.update_exchange_rate')">
            <i class="fa fa-sync"></i>
        </button>
    </div>
</div>
<div class="mb-3">
    <label>@lang('bt.invoice') @lang('bt.group')</label>
    {!! Form::select('group_id', $groups, config('bt.invoiceGroup'), ['id' => 'group_id', 'class' => 'form-select form-select-sm']) !!}
</div>
<div class="mb-3">
    <label>@lang('bt.invoice') @lang('bt.template')</label>
    {!! Form::select('template', $templates, $document->template, ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
</div>
<div class="mb-3">
    <label>@lang('bt.status')</label>
    {!! Form::select('document_status_id', $statuses, $document->document_status_id,
    ['id' => 'document_status_id', 'class' => 'form-select form-select-sm']) !!}
</div>

