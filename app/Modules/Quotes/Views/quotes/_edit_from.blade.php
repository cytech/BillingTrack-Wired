@include('quotes._js_edit_from')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">@lang('bt.from')</h3>
        <div class="card-tools float-end">
            <button class="btn btn-secondary btn-sm" id="btn-change-company-profile">
                <i class="fa fa-exchange-alt"></i> @lang('bt.change')
            </button>
        </div>
    </div>
    <div class="card-body">
        <strong>{{ $quote->companyProfile->company }}</strong><br>
        {!! $quote->companyProfile->formatted_address !!}<br>
        @lang('bt.phone'): {{ $quote->companyProfile->phone }}<br>
        @lang('bt.email'): {{ $quote->companyProfile->email }}
    </div>
</div>
