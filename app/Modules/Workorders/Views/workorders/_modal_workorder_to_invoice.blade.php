@include('workorders._js_workorder_to_invoice')

<div class="modal fade" id="modal-workorder-to-invoice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.workorder_to_invoice')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                            <label class="form-label">@lang('bt.invoice_date')</label>
                        </div>
                        <div class="col-sm-6">
                            <x-fp_common
                                    id="to_invoice_workorder_date"
                                    class="form-control"
                                    value="{{config('bt.convertWorkorderDate') == 'jobdate' ? request('job_date') : date('Y-m-d')}}">
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
                <button type="button" id="btn-workorder-to-invoice-submit"
                        class="btn btn-primary">@lang('bt.submit')</button>
            </div>
        </div>
    </div>
</div>
