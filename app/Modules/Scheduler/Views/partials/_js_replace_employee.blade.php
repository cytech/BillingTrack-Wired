<script type="text/javascript">
   ready(function () {
        const modal = bsModal('replace-employee')
        modal.show()
        document.getElementById('replace-employee-confirm').addEventListener('click', () => {
            axios.post('{{ route('scheduler.setreplace.employee') }}', {
                id: document.getElementById('item_id').value,
                resource_id: document.querySelector('#aemployee option:checked').value,
                name: document.querySelector('#aemployee option:checked').textContent,
            }).then(function (response) {
                setTimeout(function () { //give notify a chance to display before redirect
                    window.location.href = "{{ url('scheduler/checkschedule') }}";
                }, 2000);
                notify(response.data.success, 'success');
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
