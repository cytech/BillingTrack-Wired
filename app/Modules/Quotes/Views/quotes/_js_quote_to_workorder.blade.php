<script type="text/javascript">
    ready(function () {
        // Display the create quote modal
        const modal = bsModal('modal-quote-to-workorder')
        modal.show()
        // Creates the workorder
        document.getElementById('btn-quote-to-workorder-submit').addEventListener('click', () => {
            modal.hide()
            swalSaving();

            axios.post('{{ route('quoteToWorkorder.store') }}', {
                quote_id: {{ $quote_id }},
                client_id: {{ $client_id }},
                workorder_date: document.getElementById('to_workorder_date').value,
                group_id: document.getElementById('to_workorder_group_id').value,
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
