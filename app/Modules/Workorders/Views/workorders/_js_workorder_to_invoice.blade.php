<script type="text/javascript">
    ready(function () {
        // Display the create workorder modal
        const modal = bsModal('modal-workorder-to-invoice')
        modal.show()
        // Creates the invoice
        document.getElementById('btn-workorder-to-invoice-submit').addEventListener('click', () => {
            modal.hide();
            swalSaving();

            axios.post('{{ route('workorderToInvoice.store') }}', {
                workorder_id: {{ $workorder_id }},
                client_id: {{ $client_id }},
                workorder_date: document.getElementById('to_invoice_workorder_date').value,
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
