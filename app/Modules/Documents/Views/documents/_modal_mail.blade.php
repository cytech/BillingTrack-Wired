<link rel="stylesheet" href="{{ asset('plugins/tom-select/css/tom-select.bootstrap4.min.css') }}">
<script src="{{ asset('plugins/tom-select/js/tom-select.complete.min.js') }}" type="text/javascript"></script>
@include('documents._js_mail')
<div class="modal fade" id="modal-mail-document">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.email_document')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fw-bold text-end me-2">@lang('bt.to')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownTo !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fw-bold text-end me-2">@lang('bt.cc')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownCc !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fw-bold text-end me-2">@lang('bt.bcc')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownBcc !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fw-bold text-end me-2">@lang('bt.subject')</label>
                        <div class="col-sm-10">
                            {{ html()->text('subject', $subject)->class('form-control') }}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fw-bold text-end me-2">@lang('bt.body')</label>
                        <div class="col-sm-10">
                            {{ html()->textarea('body', $body)->class('form-control') }}
                        </div>
                    </div>
                    <div class="ms-5 form-check form-switch form-switch-md">
                        {{ html()->checkbox('attach_pdf', config('bt.attachPdf'), 1)->class('form-check-input') }}
                        {{ html()->label(__('bt.attach_pdf'), 'attach_pdf')->class('form-check-label fw-bold ps-3 pt-1') }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" id="btn-submit-mail-document" class="btn btn-primary"
                        data-loading-text="@lang('bt.sending')...">@lang('bt.send')</button>
            </div>
        </div>
    </div>
</div>
