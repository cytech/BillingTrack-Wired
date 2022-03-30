<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $purchaseorder->id }},
                update_company_profile_route: '{{ route('purchaseorders.purchaseorderEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('purchaseorders.purchaseorderEdit.refreshFrom') }}'
            })
        });
    });
</script>
