<script type="text/javascript">
    ready(function () {
        let attachPdf = 0;
        var tsconfig = {
            plugins: {
                remove_button: {
                    title: 'Remove this item',
                }
            },
        };

        const modal = bsModal('modal-mail-quote')
        modal.show()
        modaleL = document.getElementById('modal-mail-quote')
        modaleL.addEventListener('shown.bs.modal', function () {
            new TomSelect('#to', tsconfig);
            new TomSelect('#cc', tsconfig);
            new TomSelect('#bcc', tsconfig);
        });

        document.getElementById('btn-submit-mail-quote').addEventListener('click', (e) => {
            const btn = e.target
            btn.innerHTML = 'Sending'

            if (document.getElementById('attach_pdf').checked === true) {
                attachPdf = 1;
            }

            let to = document.getElementById('to')
            let cc = document.getElementById('cc')
            let bcc = document.getElementById('bcc')

            axios.post('{{ route('quoteMail.store') }}', {
                quote_id: {{ $quoteId }},
                to: to.tomselect.getValue(),
                cc: cc.tomselect.getValue(),
                bcc: bcc.tomselect.getValue(),
                subject: document.getElementById('subject').value,
                body: document.getElementById('body').value,
                attach_pdf: attachPdf
            }).then(function (response) {
                document.getElementById('modal-status-placeholder').innerHTML = '<div class="alert alert-success">' + '@lang('bt.sent')' + '</div>'
                setTimeout("window.location='" + decodeURIComponent('{{ $redirectTo }}') + "'", 1000);
            }).catch(function (error) {
                btn.innerHTML = 'Fail'
                showErrors(error.response.data.errors);
            });
        });
    });
</script>
