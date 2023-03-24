<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $document->id }},
                update_company_profile_route: '{{ route('documents.documentEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('documents.documentEdit.refreshFrom') }}'
            })
        });
    });
</script>
