<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-edit-vendor')
        modal.show()
        document.getElementById("form-edit-vendor").addEventListener('submit', (e) => {
            e.preventDefault()
            let formData = new FormData(e.target)
            axios.post(e.target.action, formData)
                .then(function (response) {
                    modal.hide()
                    loadModal('{{ $refreshToRoute }}', {id: {{ $id }}}, 'col-to')
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
