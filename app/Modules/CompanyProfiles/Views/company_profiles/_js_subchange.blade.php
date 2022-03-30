<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-lookup-company-profile')
        modal.show()
        addEvent(document, 'click', '#btn-submit-change-company-profile', (e) => {
            axios.post('{{ $updateCompanyProfileRoute }}', {
                company_profile_id: document.getElementById('change_company_profile_id').value,
                id: {{ $id }}
            }).then(function () {
                modal.hide()
                loadModal('{{ $refreshFromRoute }}', {id: {{ $id }}}, 'col-from')
            });
        });
    });
</script>
