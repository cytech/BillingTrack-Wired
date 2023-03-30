<script type="text/javascript">
    ready(function () {

        autosize(document.querySelectorAll('textarea'))

        document.getElementById('btn-workorder-to-invoice').addEventListener('click', () => {
            loadModal('{{ route('workorderToInvoice.create') }}', {
                workorder_id: {{ $workorder->id }},
                client_id: {{ $workorder->client_id }},
                job_date: '{{ $workorder->job_date }}'
            })
        });

        document.getElementById('btn-update-exchange-rate').addEventListener('click', () => {
            updateExchangeRate();
        });

        document.getElementById('currency_code').addEventListener('change', () => {
            updateExchangeRate();
        });

        function updateExchangeRate() {
            axios.post('{{ route('currencies.getExchangeRate') }}', {
                currency_code: document.getElementById('currency_code').value
            }).then(function (response) {
                document.getElementById('exchange_rate').value = response.data;
            });
        }

        addEvent(document, 'click', ".btn-save-workorder", (e) => {
            const items = [];
            let display_order = 1;
            const custom_fields = {};
            const apply_exchange_rate = e.target.dataset.applyExchangeRate

            document.querySelectorAll('table tr.item').forEach((item) => {
                const row = {};
                // check for save item as lookup checkbox, removed with livewire handling it
                item.querySelectorAll('input,select,textarea').forEach((e) => {
                    if (e.name !== undefined) {
                        row[e.name] = e.value
                    }
                });
                row['display_order'] = display_order;
                display_order++;
                items.push(row);
            });

            document.querySelectorAll('.custom-form-field').forEach((e) => {
                const fieldName = e.dataset.customformFieldName
                if (fieldName !== undefined) {
                    custom_fields[e.dataset.customformFieldName] = e.value
                }
            });

            let willcall = 0;
            if (document.getElementById('will_call').checked === true) {
                willcall = 1;
            }

            swalSaving();

            let data = {
                number: document.getElementById('number').value,
                workorder_date: document.getElementById('workorder_date').value,
                expires_at: document.getElementById('expires_at').value,
                workorder_status_id: document.getElementById('workorder_status_id').value,
                items: items,
                terms: document.getElementById('terms').value,
                footer: document.getElementById('footer').value,
                currency_code: document.getElementById('currency_code').value,
                exchange_rate: document.getElementById('exchange_rate').value,
                custom: custom_fields,
                apply_exchange_rate: apply_exchange_rate,
                template: document.getElementById('template').value,
                summary: document.getElementById('summary').value,
                discount: document.getElementById('discount').value,
                job_date: document.getElementById('job_date').value,
                start_time: document.getElementById('start_time').value,
                end_time: document.getElementById('end_time').value,
                will_call: willcall
            }
            axios.post('{{ route('workorders.update', [$workorder->id]) }}', data).then(function () {
                axios.get('{{ route('workorders.workorderEdit.refreshEdit', [$workorder->id]) }}')
                    .then(response => {
                        setInnerHTML(document.getElementById('div-workorder-edit'), response.data)
                        //window.livewire.rescan();
                        window.location.reload()
                        Swal.close()
                    })
            }).catch(function (error) {
                if (error.response.status === 422) {
                    let msg = ''
                    for (let [id, message] of Object.entries(error.response.data.errors)) {
                        msg += message + '<br>';
                    }
                    notify(msg, 'error');
                } else {
                    notify('@lang('bt.unknown_error')', 'error');
                }
            });
        });

        var el = document.getElementById("tbody");
        Sortable.create(el, {
            handle: '.handle',
            animation: 150
        });
    });
</script>
