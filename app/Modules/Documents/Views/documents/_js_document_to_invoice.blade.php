<script type="text/javascript">
    ready(function () {
        // Display the create document modal
        const modal = bsModal('modal-document-to-invoice')
        modal.show()
        // Creates the invoice
        document.getElementById('btn-document-to-invoice-submit').addEventListener('click', () => {
            modal.hide();
            swalSaving();

            axios.post('{{ route('documentToInvoice.store') }}', {
                document_id: {{ $document_id }},
                client_id: {{ $client_id }},
                document_date: document.getElementById('to_invoice_date').value,
                group_id: document.getElementById('to_invoice_group_id').value,
                user_id: {{ $user_id }}
            }).then(function (response) {
                window.location = response.data.redirectTo;
            }).catch(function (error) {
                if (error.response.status === 422) {
                    showErrors(error.response.data.errors);
                } else {
                    notify('@lang('bt.unknown_error')', 'error');
                }
            });
        });
    });
</script>
