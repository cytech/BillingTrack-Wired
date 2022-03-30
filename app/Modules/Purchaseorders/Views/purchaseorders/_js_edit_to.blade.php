<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-edit-vendor').addEventListener('click', (e) => {
            loadModal('{{ route('vendors.ajax.modalEdit') }}', {
                vendor_id: e.target.dataset.vendorId,
                refresh_to_route: '{{ route('purchaseorders.purchaseorderEdit.refreshTo') }}',
                id: {{ $purchaseorder->id }}
            })
        });
        window.livewire.on('resource-changed', id => {
            loadModal('{{ route('purchaseorders.purchaseorderEdit.refreshTo') }}', {id: id}, 'col-to')
        })
    });
</script>
