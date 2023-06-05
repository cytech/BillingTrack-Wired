<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_invoice_template'): </label>
            {{ html()->select('setting[invoiceTemplate]', $invoiceTemplates, config('bt.invoiceTemplate'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_group'): </label>
            {{ html()->select('setting[invoiceGroup]', $groups, config('bt.invoiceGroup'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.invoices_due_after'): </label>
            {{ html()->text('setting[invoicesDueAfter]', config('bt.invoicesDueAfter'))->class('form-control') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_status_filter'): </label>
            {{ html()->select('setting[invoiceStatusFilter]', $invoiceStatuses, config('bt.invoiceStatusFilter'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.update_inv_products'): </label>
            {{ html()->select('setting[updateInvProductsDefault]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.updateInvProductsDefault'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_terms'): </label>
    {{ html()->textarea('setting[invoiceTerms]', config('bt.invoiceTerms'))->rows(2)->class('form-control') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_footer'): </label>
    {{ html()->textarea('setting[invoiceFooter]', config('bt.invoiceFooter'))->rows(2)->class('form-control') }}
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.automatic_email_on_recur'): </label>
            {{ html()->select('setting[automaticEmailOnRecur]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.automaticEmailOnRecur'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.automatic_email_payment_receipts'): </label>
            {{ html()->select('setting[automaticEmailPaymentReceipts]', ['0' => trans('bt.no'), '1' => trans('bt.yes')], config('bt.automaticEmailPaymentReceipts'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.online_payment_method'): </label>
            {{ html()->select('setting[onlinePaymentMethod]', $paymentMethods, config('bt.onlinePaymentMethod'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.allow_payments_without_balance'): </label>
            {{ html()->select('setting[allowPaymentsWithoutBalance]', $yesNoArray, config('bt.allowPaymentsWithoutBalance'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.if_invoice_is_emailed_while_draft'): </label>
            {{ html()->select('setting[resetInvoiceDateEmailDraft]', $invoiceWhenDraftOptions, config('bt.resetInvoiceDateEmailDraft'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.recalculate_invoices'): </label><br>
            @if (!config('app.demo'))
                <button type="button" class="btn btn-secondary" id="btn-recalculate-invoices"
                        data-loading-text="@lang('bt.recalculating_wait')">@lang('bt.recalculate')</button>
            @else
                Recalculate is disabled in the demo.
            @endif
            <p class="form-text text-muted">@lang('bt.recalculate_help_text')</p>
        </div>
    </div>
</div>
