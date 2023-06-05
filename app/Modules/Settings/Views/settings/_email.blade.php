@section('javaScript')
    @parent
    <script type="text/javascript">
        ready(function () {
            document.getElementById('mailPassword').value = ''

            updateEmailOptions();

            addEvent(document, 'change', "#mailDriver", (e) => {
                updateEmailOptions();
            });

            function updateEmailOptions() {
                document.querySelectorAll('.email-option').forEach(function (e) {
                    e.style.display = 'none'
                })

                mailDriver = document.getElementById('mailDriver').value

                if (mailDriver === 'smtp') {
                    document.querySelectorAll('.smtp-option').forEach(function (e) {
                        e.style.display = 'flex'
                    })
                } else if (mailDriver === 'sendmail') {
                    document.querySelectorAll('.sendmail-option').forEach(function (e) {
                        e.style.display = 'flex'
                    })
                } else if (mailDriver === 'mail') {
                    document.querySelectorAll('.phpmail-option').forEach(function (e) {
                        e.style.display = 'flex'
                    })
                }
            }
        });
    </script>
@stop

<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.email_send_method'): </label>
    @if (!config('app.demo'))
        {{ html()->select('setting[mailDriver]', $emailSendMethods, config('bt.mailDriver'))->class('form-select')->attribute('id', 'mailDriver') }}
    @else
        Email is disabled in the demo. Options are SMTP, PHPMail and Sendmail
    @endif
</div>
<div class="row smtp-option email-option mb-3">
    <div class="col-md-9">
        <label class="form-label fw-bold">@lang('bt.smtp_host_address'): </label>
        {{ html()->text('setting[mailHost]', config('bt.mailHost'))->class('form-control') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_host_port'): </label>
        {{ html()->text('setting[mailPort]', config('bt.mailPort'))->class('form-control') }}
    </div>
</div>
<div class="row smtp-option email-option mb-3">
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_username'): </label>
        {{ html()->text('setting[mailUsername]', config('bt.mailUsername'))->class('form-control') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_password'): </label>
        {{ html()->password('setting[mailPassword]')->class('form-control') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_encryption'): </label>
        {{ html()->select('setting[mailEncryption]', $emailEncryptions, config('bt.mailEncryption'))->class('form-select') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.allow_self_signed_cert'): </label>
        {{ html()->select('setting[mailAllowSelfSignedCertificate]', $yesNoArray, config('bt.mailAllowSelfSignedCertificate'))->class('form-select') }}
    </div>
</div>
<div class="mb-3 sendmail-option email-option">
    <div class="mb-3">
        <label class="form-label fw-bold">@lang('bt.sendmail_path'): </label>
        {{ html()->text('setting[mailSendmail]', config('bt.mailSendmail'))->class('form-control') }}
    </div>
</div>
<div class="row smtp-option sendmail-option phpmail-option email-option mb-3">
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_attach_pdf'): </label>
        {{ html()->select('setting[attachPdf]', $yesNoArray, config('bt.attachPdf'))->class('form-select')->attribute('id', 'attachPdf') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.reply_to_address'): </label>
        {{ html()->text('setting[mailReplyToAddress]', config('bt.mailReplyToAddress'))->class('form-control') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_cc'): </label>
        {{ html()->text('setting[mailDefaultCc]', config('bt.mailDefaultCc'))->class('form-control') }}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_bcc'): </label>
        {{ html()->text('setting[mailDefaultBcc]', config('bt.mailDefaultBcc'))->class('form-control') }}
    </div>
</div>
<hr>
<div class="card">
    <div class="card-header d-flex p-0 justify-content-center">
        <ul class="nav nav-pills" id="template-tabs">
            <li class="nav-item"><a class="nav-link active show" data-bs-toggle="tab"
                                    href="#tab-quotetemplates">@lang('bt.quote_templates')</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                    href="#tab-workordertemplates">@lang('bt.workorder_templates')</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                    href="#tab-invoicetemplates">@lang('bt.invoice_templates')</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                    href="#tab-purchaseordertemplates">@lang('bt.purchaseorder_templates')</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                    href="#tab-overduetemplates">@lang('bt.overdue_templates')</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                    href="#tab-paymenttemplates">@lang('bt.payment_templates')</a></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content m-2">
            <div id="tab-quotetemplates" class="tab-pane active">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.quote_email_subject'): </label>
                            {{ html()->text('setting[quoteEmailSubject]', config('bt.quoteEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_quote_email_body'): </label>
                            {{ html()->textarea('setting[quoteEmailBody]', config('bt.quoteEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.quote_approved_email_body'): </label>
                            {{ html()->textarea('setting[quoteApprovedEmailBody]', config('bt.quoteApprovedEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.quote_rejected_email_body'): </label>
                            {{ html()->textarea('setting[quoteRejectedEmailBody]', config('bt.quoteRejectedEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-workordertemplates" class="tab-pane">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.workorder_email_subject'): </label>
                            {{ html()->text('setting[workorderEmailSubject]', config('bt.workorderEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_workorder_email_body'): </label>
                            {{ html()->textarea('setting[workorderEmailBody]', config('bt.workorderEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.workorder_approved_email_body'): </label>
                            {{ html()->textarea('setting[workorderApprovedEmailBody]', config('bt.workorderApprovedEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.workorder_rejected_email_body'): </label>
                            {{ html()->textarea('setting[workorderRejectedEmailBody]', config('bt.workorderRejectedEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-invoicetemplates" class="tab-pane">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.invoice_email_subject'): </label>
                            {{ html()->text('setting[invoiceEmailSubject]', config('bt.invoiceEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_invoice_email_body'): </label>
                            {{ html()->textarea('setting[invoiceEmailBody]', config('bt.invoiceEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-purchaseordertemplates" class="tab-pane">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.purchaseorder_email_subject'): </label>
                            {{ html()->text('setting[purchaseorderEmailSubject]', config('bt.purchaseorderEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#purchaseorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_purchaseorder_email_body'): </label>
                            {{ html()->textarea('setting[purchaseorderEmailBody]', config('bt.purchaseorderEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#purchaseorder-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-overduetemplates" class="tab-pane">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.overdue_email_subject'): </label>
                            {{ html()->text('setting[overdueInvoiceEmailSubject]', config('bt.overdueInvoiceEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_overdue_invoice_email_body')
                                : </label>
                            {{ html()->textarea('setting[overdueInvoiceEmailBody]', config('bt.overdueInvoiceEmailBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.overdue_invoice_reminder_frequency')
                                : </label>
                            {{ html()->text('setting[overdueInvoiceReminderFrequency]', config('bt.overdueInvoiceReminderFrequency'))->class('form-control') }}
                            <span class="form-text text-muted">@lang('bt.overdue_invoice_reminder_frequency_help')</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-paymenttemplates" class="tab-pane">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.payment_receipt_email_subject'): </label>
                            {{ html()->text('setting[paymentReceiptEmailSubject]', config('bt.paymentReceiptEmailSubject'))->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#payment-receipt-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.default_payment_receipt_body'): </label>
                            {{ html()->textarea('setting[paymentReceiptBody]', config('bt.paymentReceiptBody'))->rows(5)->class('form-control') }}
                            <span class="form-text text-muted"><a
                                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#payment-receipt-email-template"
                                        target="_blank">@lang('bt.available_fields')</a></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_email_subject')
                                    : </label>
                                {{ html()->text('setting[upcomingPaymentNoticeEmailSubject]', config('bt.upcomingPaymentNoticeEmailSubject'))->class('form-control') }}
                                <span class="form-text text-muted"><a
                                            href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                            target="_blank">@lang('bt.available_fields')</a></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_email_body')
                                    : </label>
                                {{ html()->textarea('setting[upcomingPaymentNoticeEmailBody]', config('bt.upcomingPaymentNoticeEmailBody'))->rows(5)->class('form-control') }}
                                <span class="form-text text-muted"><a
                                            href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                                            target="_blank">@lang('bt.available_fields')</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_frequency')
                                    : </label>
                                {{ html()->text('setting[upcomingPaymentNoticeFrequency]', config('bt.upcomingPaymentNoticeFrequency'))->class('form-control') }}
                                <span class="form-text text-muted">@lang('bt.upcoming_payment_notice_frequency_help')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
