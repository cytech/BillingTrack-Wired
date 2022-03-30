@include('quotes._js_quote_to_invoice')
<div class="modal fade" id="modal-quote-to-invoice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.quote_to_invoice')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                            <label class="form-label">@lang('bt.date')</label>
                        </div>
                        <div class="col-sm-6">
                            <x-fp_common
                                    id="to_invoice_date"
                                    class="form-control">
                            </x-fp_common>
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                            <label class="form-label">@lang('bt.group')</label>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::select('group_id', $groups, config('bt.invoiceGroup'), ['id' => 'to_invoice_group_id', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" id="btn-quote-to-invoice-submit"
                        class="btn btn-primary">@lang('bt.submit')</button>
            </div>
        </div>
    </div>
</div>
