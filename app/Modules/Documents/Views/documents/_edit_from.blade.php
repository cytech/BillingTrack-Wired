@include('documents._js_edit_from')
<div class="row">
    <div class="col">
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
                <strong>{{ $document->companyProfile->company }}</strong><br>
                {!! $document->companyProfile->formatted_address !!}<br>
                @lang('bt.phone'): {{ $document->companyProfile->phone }}<br>
                @lang('bt.email'): {{ $document->companyProfile->email }}
            </div>
        </div>
    </div>
    @if($document->module_type == 'Purchaseorder')
        <div class="col">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.ship_to')</h3>
                </div>
                <div class="card-body">
                    <strong>{{ $document->companyProfile->company }}</strong><br>
                    @if($document->companyProfile->formatted_address2)
                        {!! $document->companyProfile->formatted_address2 !!}
                    @else
                        {!! $document->companyProfile->formatted_address !!}<br>
                    @endif<br>
                    @lang('bt.phone'): {{ $document->companyProfile->phone }}<br>
                    {{--            @lang('bt.email'): {{ $document->companyProfile->email }}--}}
                </div>
            </div>
        </div>
    @endif
</div>
