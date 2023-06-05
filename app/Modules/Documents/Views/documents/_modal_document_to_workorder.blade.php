@include('documents._js_document_to_workorder')
<div class="modal fade" id="modal-document-to-workorder">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$title}}</h4>
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
                                    id="to_workorder_date"
                                    class="form-control">
                            </x-fp_common>
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-sm-4 text-end fw-bold">
                        <label class="form-label">@lang('bt.group')</label>
                        </div>
                        <div class="col-sm-6">
                            {{ html()->select('group_id', $groups, config('bt.workorderGroup'))->class('form-select')->attribute('id', 'to_workorder_group_id') }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" id="btn-document-to-workorder-submit"
                        class="btn btn-primary">@lang('bt.submit')</button>
            </div>
        </div>
    </div>
</div>
