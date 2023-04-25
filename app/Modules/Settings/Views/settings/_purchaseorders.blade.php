<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_purchaseorder_template'): </label>
            {!! Form::select('setting[purchaseorderTemplate]', $purchaseorderTemplates, config('bt.purchaseorderTemplate'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_group'): </label>
            {!! Form::select('setting[purchaseorderGroup]', $groups, config('bt.purchaseorderGroup'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.purchaseorders_due_after'): </label>
            {!! Form::text('setting[purchaseordersDueAfter]', config('bt.purchaseordersDueAfter'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_status_filter'): </label>
            {!! Form::select('setting[purchaseorderStatusFilter]', $purchaseorderStatuses, config('bt.purchaseorderStatusFilter'), ['class' => 'form-select']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.update_products'): </label>
            {!! Form::select('setting[updateProductsDefault]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.updateProductsDefault'), ['class' => 'form-select']) !!}
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_terms'): </label>
    {!! Form::textarea('setting[purchaseorderTerms]', config('bt.purchaseorderTerms'), ['class' => 'form-control', 'rows' => 2]) !!}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_footer'): </label>
    {!! Form::textarea('setting[purchaseorderFooter]', config('bt.purchaseorderFooter'), ['class' => 'form-control', 'rows' => 2]) !!}
</div>
{{--<div class="row">--}}
{{--    <div class="col-md-3">--}}
{{--        <div class="mb-3">--}}
{{--            <label class="form-label fw-bold">@lang('bt.automatic_email_on_recur'): </label>--}}
{{--            {!! Form::select('setting[automaticEmailOnRecur]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.automaticEmailOnRecur'), ['class' => 'form-control']) !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-3">--}}
{{--        <div class="mb-3">--}}
{{--            <label class="form-label fw-bold">@lang('bt.automatic_email_payment_receipts'): </label>--}}
{{--            {!! Form::select('setting[automaticEmailPaymentReceipts]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.automaticEmailPaymentReceipts'), ['class' => 'form-control']) !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-3">--}}
{{--        <div class="mb-3">--}}
{{--            <label class="form-label fw-bold">@lang('bt.online_payment_method'): </label>--}}
{{--            {!! Form::select('setting[onlinePaymentMethod]', $paymentMethods, config('bt.onlinePaymentMethod'), ['class' => 'form-control']) !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-3">--}}
{{--        <div class="mb-3">--}}
{{--            <label class="form-label fw-bold">@lang('bt.allow_payments_without_balance'): </label>--}}
{{--            {!! Form::select('setting[allowPaymentsWithoutBalance]', $yesNoArray, config('bt.allowPaymentsWithoutBalance'), ['class' => 'form-control']) !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.if_purchaseorder_is_emailed_while_draft'): </label>
            {!! Form::select('setting[resetPurchaseorderDateEmailDraft]', $purchaseorderWhenDraftOptions, config('bt.resetPurchaseorderDateEmailDraft'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.recalculate_purchaseorders'): </label><br>
            @if (!config('app.demo'))
                <button type="button" class="btn btn-secondary" id="btn-recalculate-purchaseorders"
                        data-loading-text="@lang('bt.recalculating_wait')">@lang('bt.recalculate')</button>
            @else
                Recalculate is disabled in the demo.
            @endif
            <p class="form-text text-muted">@lang('bt.recalculate_help_text')</p>
        </div>
    </div>
</div>
