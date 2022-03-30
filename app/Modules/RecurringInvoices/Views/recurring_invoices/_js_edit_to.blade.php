<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-edit-client').addEventListener('click', (e) => {
            loadModal('{{ route('clients.ajax.modalEdit') }}', {
                client_id: e.target.dataset.clientId,
                refresh_to_route: '{{ route('recurringInvoices.recurringInvoiceEdit.refreshTo') }}',
                id: {{ $recurringInvoice->id }}
            })
        });
        window.livewire.on('resource-changed', id => {
            loadModal('{{ route('recurringInvoices.recurringInvoiceEdit.refreshTo') }}', {id: id}, 'col-to')
        })
    });
</script>
