<link rel="stylesheet" href="{{ asset('plugins/tom-select/css/tom-select.bootstrap4.min.css') }}">
<script src="{{ asset('plugins/tom-select/js/tom-select.complete.min.js') }}" type="text/javascript"></script>
@include('payments._js_mail')
<div class="modal fade" id="modal-mail-payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.email_payment_receipt')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fs-5 fw-bold text-end me-2">@lang('bt.to')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownTo !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fs-5 fw-bold text-end me-2">@lang('bt.cc')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownCc !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fs-5 fw-bold text-end me-2">@lang('bt.bcc')</label>
                        <div class="col-sm-10">
                            {!! $contactDropdownBcc !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fs-5 fw-bold text-end me-2">@lang('bt.subject')</label>
                        <div class="col-sm-10">
                            {!! Form::text('subject', $subject, ['id' => 'subject', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label col-sm-2 fs-5 fw-bold text-end me-2">@lang('bt.body')</label>
                        <div class="col-sm-10">
                            {!! Form::textarea('body', $body, ['id' => 'body', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="ms-5 form-check form-switch form-switch-md">
                        {{ Form::checkbox('attach_pdf', 1, config('bt.attachPdf'), ['id' => 'attach_pdf', 'class' => 'form-check-input']) }}
                        {{ Form::label('attach_pdflabel', trans('bt.attach_pdf'), ['class' => 'form-check-label fw-bold ps-3 pt-1', 'for' => 'attach_pdf']) }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" id="btn-submit-mail-payment" class="btn btn-primary"
                        data-loading-text="@lang('bt.sending')...">@lang('bt.send')</button>
            </div>
        </div>
    </div>
</div>
