@include('vendors._js_subedit')
{!! Form::model($vendor, ['route' => ['vendors.ajax.modalUpdate', $vendor->id], 'id' => 'form-edit-vendor']) !!}
<div class="modal" id="modal-edit-vendor">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.edit_vendor')</h4>
                <div class="float-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                    <input type="submit" id="btn-edit-vendor-submit" class="btn btn-primary" value="@lang('bt.save')">
                </div>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                @include('vendors._form')
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
