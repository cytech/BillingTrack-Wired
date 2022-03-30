<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $invoice->id }},
                update_company_profile_route: '{{ route('invoices.invoiceEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('invoices.invoiceEdit.refreshFrom') }}'
            })
        });
    });
</script>
