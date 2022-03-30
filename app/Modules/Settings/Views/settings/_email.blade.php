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
        {!! Form::select('setting[mailDriver]', $emailSendMethods, config('bt.mailDriver'), ['id' => 'mailDriver', 'class' => 'form-select']) !!}
    @else
        Email is disabled in the demo. Options are SMTP, PHPMail and Sendmail
    @endif
</div>
<div class="row smtp-option email-option mb-3">
    <div class="col-md-9">
        <label class="form-label fw-bold">@lang('bt.smtp_host_address'): </label>
        {!! Form::text('setting[mailHost]', config('bt.mailHost'), ['class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_host_port'): </label>
        {!! Form::text('setting[mailPort]', config('bt.mailPort'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row smtp-option email-option mb-3">
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_username'): </label>
        {!! Form::text('setting[mailUsername]', config('bt.mailUsername'), ['class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_password'): </label>
        {!! Form::password('setting[mailPassword]', ['id' => 'mailPassword', 'class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.smtp_encryption'): </label>
        {!! Form::select('setting[mailEncryption]', $emailEncryptions, config('bt.mailEncryption'), ['class' => 'form-select']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.allow_self_signed_cert'): </label>
        {!! Form::select('setting[mailAllowSelfSignedCertificate]', $yesNoArray, config('bt.mailAllowSelfSignedCertificate'), ['class' => 'form-select']) !!}
    </div>
</div>
<div class="mb-3 sendmail-option email-option">
    <div class="mb-3">
        <label class="form-label fw-bold">@lang('bt.sendmail_path'): </label>
        {!! Form::text('setting[mailSendmail]', config('bt.mailSendmail'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row smtp-option sendmail-option phpmail-option email-option mb-3">
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_attach_pdf'): </label>
        {!! Form::select('setting[attachPdf]', $yesNoArray, config('bt.attachPdf'), ['id' => 'attachPdf', 'class' => 'form-select']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.reply_to_address'): </label>
        {!! Form::text('setting[mailReplyToAddress]', config('bt.mailReplyToAddress'), ['class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_cc'): </label>
        {!! Form::text('setting[mailDefaultCc]', config('bt.mailDefaultCc'), ['class' => 'form-control']) !!}
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">@lang('bt.always_bcc'): </label>
        {!! Form::text('setting[mailDefaultBcc]', config('bt.mailDefaultBcc'), ['class' => 'form-control']) !!}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.quote_email_subject'): </label>
            {!! Form::text('setting[quoteEmailSubject]', config('bt.quoteEmailSubject'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.invoice_email_subject'): </label>
            {!! Form::text('setting[invoiceEmailSubject]', config('bt.invoiceEmailSubject'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_quote_email_body'): </label>
            {!! Form::textarea('setting[quoteEmailBody]', config('bt.quoteEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_invoice_email_body'): </label>
            {!! Form::textarea('setting[invoiceEmailBody]', config('bt.invoiceEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.overdue_email_subject'): </label>
            {!! Form::text('setting[overdueInvoiceEmailSubject]', config('bt.overdueInvoiceEmailSubject'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_email_subject'): </label>
            {!! Form::text('setting[upcomingPaymentNoticeEmailSubject]', config('bt.upcomingPaymentNoticeEmailSubject'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_overdue_invoice_email_body'): </label>
            {!! Form::textarea('setting[overdueInvoiceEmailBody]', config('bt.overdueInvoiceEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_email_body'): </label>
            {!! Form::textarea('setting[upcomingPaymentNoticeEmailBody]', config('bt.upcomingPaymentNoticeEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#invoice-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.overdue_invoice_reminder_frequency'): </label>
            {!! Form::text('setting[overdueInvoiceReminderFrequency]', config('bt.overdueInvoiceReminderFrequency'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted">@lang('bt.overdue_invoice_reminder_frequency_help')</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.upcoming_payment_notice_frequency'): </label>
            {!! Form::text('setting[upcomingPaymentNoticeFrequency]', config('bt.upcomingPaymentNoticeFrequency'), ['class' => 'form-control']) !!}
            <span class="form-text text-muted">@lang('bt.upcoming_payment_notice_frequency_help')</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.quote_approved_email_body'): </label>
            {!! Form::textarea('setting[quoteApprovedEmailBody]', config('bt.quoteApprovedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.quote_rejected_email_body'): </label>
            {!! Form::textarea('setting[quoteRejectedEmailBody]', config('bt.quoteRejectedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#quote-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.workorder_approved_email_body'): </label>
            {!! Form::textarea('setting[workorderApprovedEmailBody]', config('bt.workorderApprovedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.workorder_rejected_email_body'): </label>
            {!! Form::textarea('setting[workorderRejectedEmailBody]', config('bt.workorderRejectedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
            <span class="form-text text-muted"><a
                        href="{{ url('documentation',['page' => 'Email-Templates'])}}#workorder-email-template"
                        target="_blank">@lang('bt.available_fields')</a></span>
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.payment_receipt_email_subject'): </label>
    {!! Form::text('setting[paymentReceiptEmailSubject]', config('bt.paymentReceiptEmailSubject'), ['class' => 'form-control']) !!}
    <span class="form-text text-muted"><a
                href="{{ url('documentation',['page' => 'Email-Templates'])}}#payment-receipt-email-template"
                target="_blank">@lang('bt.available_fields')</a></span>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_payment_receipt_body'): </label>
    {!! Form::textarea('setting[paymentReceiptBody]', config('bt.paymentReceiptBody'), ['class' => 'form-control', 'rows' => 5]) !!}
    <span class="form-text text-muted"><a
                href="{{ url('documentation',['page' => 'Email-Templates'])}}#payment-receipt-email-template"
                target="_blank">@lang('bt.available_fields')</a></span>
</div>
