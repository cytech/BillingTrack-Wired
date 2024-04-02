@include('vendors._js_subedit')
{{ html()->modelForm($vendor, 'POST', route('vendors.ajax.modalUpdate', $vendor->id))->attribute('id', 'form-edit-vendor')->open() }}
<div class="modal" id="modal-edit-vendor">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title col-12">
                <h4 class="ms-3 float-start">@lang('bt.edit_vendor')</h4>
                    <button type="button" class="btn btn-secondary me-3 float-end" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                    <input type="submit" id="btn-edit-vendor-submit" class="btn btn-primary me-3 float-end" value="@lang('bt.save')">
                </div>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                @include('vendors._form')
            </div>
        </div>
    </div>
</div>
{{ html()->closeModelForm() }}
