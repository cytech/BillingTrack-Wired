<div class="card card-outline card-primary">
    <div class="card-body">
        <span class="float-start"><strong>@lang('bt.subtotal')</strong></span><span
            class="float-end">{{ $recurringInvoice->amount->formatted_subtotal }}</span>

        <div class="clearfix"></div>

        @if ($recurringInvoice->discount > 0)
            <span class="float-start"><strong>@lang('bt.discount')</strong></span><span
                class="float-end">{{ $recurringInvoice->amount->formatted_discount }}</span>

            <div class="clearfix"></div>
        @endif

        <span class="float-start"><strong>@lang('bt.tax')</strong></span><span
            class="float-end">{{ $recurringInvoice->amount->formatted_tax }}</span>

        <div class="clearfix"></div>
        <span class="float-start"><strong>@lang('bt.total')</strong></span><span
            class="float-end">{{ $recurringInvoice->amount->formatted_total }}</span>

        <div class="clearfix"></div>
    </div>
</div>
