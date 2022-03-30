<script type="text/javascript">
    ready(function () {
        document.getElementById('btn-change-company-profile').addEventListener('click', () => {
            loadModal('{{ route('companyProfiles.ajax.modalLookup') }}', {
                id: {{ $workorder->id }},
                update_company_profile_route: '{{ route('workorders.workorderEdit.updateCompanyProfile') }}',
                refresh_from_route: '{{ route('workorders.workorderEdit.refreshFrom') }}'
            })
        });
    });
</script>
