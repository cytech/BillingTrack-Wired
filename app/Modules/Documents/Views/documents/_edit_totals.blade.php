<div class="card card-outline card-primary">
    <div class="card-body">
        <span class="float-start"><strong>@lang('bt.subtotal')</strong></span><span
                class="float-end">{{ $document->amount->formatted_subtotal }}</span>
        <div class="clearfix"></div>
        @if ($document->discount > 0)
            <span class="float-start"><strong>@lang('bt.discount')</strong></span><span
                    class="float-end">{{ $document->amount->formatted_discount }}</span>

            <div class="clearfix"></div>
        @endif
        <span class="float-start"><strong>@lang('bt.tax')</strong></span><span
                class="float-end">{{ $document->amount->formatted_tax }}</span>
        <div class="clearfix"></div>
        <span class="float-start"><strong>@lang('bt.total')</strong></span><span
                class="float-end">{{ $document->amount->formatted_total }}</span>

        @if($document->module_type == 'Invoice' or $document->module_type == 'Purchaseorder')
            <div class="clearfix"></div>
            <span class="float-start"><strong>@lang('bt.paid')</strong></span><span
                    class="float-end">{{ $document->amount->formatted_paid }}</span>

            <div class="clearfix"></div>
            <span class="float-start"><strong>@lang('bt.balance')</strong></span><span
                    class="float-end">{{ $document->amount->formatted_balance }}</span>
        @endif

        <div class="clearfix"></div>
    </div>
</div>
