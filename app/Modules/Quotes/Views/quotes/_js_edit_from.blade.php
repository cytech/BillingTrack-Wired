<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $quote->id }},
                update_company_profile_route: '{{ route('quotes.quoteEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('quotes.quoteEdit.refreshFrom') }}'
            })
        });
    });
</script>
