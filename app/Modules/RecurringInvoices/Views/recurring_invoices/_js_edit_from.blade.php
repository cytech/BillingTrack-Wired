<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $recurringInvoice->id }},
                update_company_profile_route: '{{ route('recurringInvoices.recurringInvoiceEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('recurringInvoices.recurringInvoiceEdit.refreshFrom') }}'
            })
        });
    });
</script>
