<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_group'): </label>
            {{ html()->select('setting[recurringinvoiceGroup]', $groups, config('bt.recurringinvoiceGroup'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_frequency'): </label>
            {{ html()->select('setting[recurringinvoiceFrequency]', array_combine(range(1, 90), range(1, 90)), config('bt.recurringinvoiceFrequency'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_period'): </label>
            {{ html()->select('setting[recurringinvoicePeriod]', $periods, config('bt.recurringinvoicePeriod'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_status_filter'): </label>
            {{ html()->select('setting[recurringinvoiceStatusFilter]', $recurringinvoiceStatuses, config('bt.recurringinvoiceStatusFilter'))->class('form-select') }}
        </div>
    </div>
</div>
