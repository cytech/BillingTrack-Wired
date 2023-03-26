<script type="text/javascript">
    ready(function () {
        // Display the create document modal
        const modal = bsModal('modal-document-to-workorder')
        modal.show()
        // Creates the workorder
        document.getElementById('btn-document-to-workorder-submit').addEventListener('click', () => {
            modal.hide()
            swalSaving();

            axios.post('{{ route('documentToWorkorder.store') }}', {
                document_id: {{ $document_id }},
                client_id: {{ $client_id }},
                document_date: document.getElementById('to_workorder_date').value,
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
