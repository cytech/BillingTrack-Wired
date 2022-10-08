<script type="text/javascript">
    ready(function () {
        clientId = {{ isset($client) ? $client->id : 0 }};
        stopHideUniqueName = false;

        if (document.getElementById('unique_name').value === '' || clientId === 0) {
            hasUniqueName = false;
        } else {
            hasUniqueName = true;
        }

        function clientNameIsDuplicate(name, callback) {
            axios.post('{{ route('clients.ajax.checkDuplicateName') }}', {
                client_name: name,
                client_id: clientId
            }).then(callback);
        }

        function checkClientNameIsDuplicate(name) {
            clientNameIsDuplicate(name, function (response) {
                if (response.data.is_duplicate === 1) {
                    document.getElementById('help_text_client_unique_name').innerHTML = '@lang('bt.duplicate_found')' + '<br/>'
                        + '@lang('bt.help_text_client_unique_name')';
                    showUniqueName();
                } else {
                    hideUniqueName();
                }
            });
        }

        function showUniqueName() {
            document.getElementById('btn-show-unique-name').style.visibility = 'hidden'
            document.getElementById('col-client-unique-name').style.display = 'flex'
            stopHideUniqueName = true;
        }

        function hideUniqueName() {
            if (stopHideUniqueName === false) {
                document.getElementById('col-client-unique-name').style.display = 'none'
            }
        }

        addEvent(document, 'input', '#name', (e) => {
            if (hasUniqueName === false) {
                document.getElementById('unique_name_suf').value = Math.random().toString(36).slice(2, 7)
            }
            document.getElementById('unique_name_pre').value = e.target.value.slice(0, 10) + '_'
            document.getElementById('unique_name').value = document.getElementById('unique_name_pre').value + document.getElementById('unique_name_suf').value
        });

        addEvent(document, 'focusout', '#unique_name_suf', (e) => {
            if (e.target.value === '') {
                e.target.value = Math.random().toString(36).slice(2, 7)
            }
            document.getElementById('unique_name').value = document.getElementById('unique_name_pre').value + e.target.value

        });

        addEvent(document, 'click', '#btn-show-unique-name', (e) => {
            showUniqueName();
            e.target.style.visibility = 'hidden'
            stopHideUniqueName = true;
        });

        if ({{config('bt.displayClientUniqueName')}})
            showUniqueName();
        else
            checkClientNameIsDuplicate(document.getElementById('name').value);

        addEvent(document, 'focusout', '#name', (e) => {
            checkClientNameIsDuplicate(e.target.value);
        });

        if (document.getElementById('name').value !== document.getElementById('unique_name').value) {
            showUniqueName();
        }
    });
</script>
