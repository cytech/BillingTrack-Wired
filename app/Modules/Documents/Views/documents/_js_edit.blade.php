<script type="text/javascript">
    ready(function () {

        autosize(document.querySelectorAll('textarea'))

        document.getElementById('btn-document-to-invoice')?.addEventListener('click', () => {
            loadModal('{{ route('documentToInvoice.create') }}', {
                title: '@lang('bt.'. $document->lower_case_baseclass . '_to_invoice')',
                document_id: {{ $document->id }},
                client_id: {{ $document->client_id }}
            })
        });

        document.getElementById('btn-document-to-workorder')?.addEventListener('click', () => {
            loadModal('{{ route('documentToWorkorder.create') }}', {
                title: '@lang('bt.'. $document->lower_case_baseclass .'_to_workorder')',
                document_id: {{ $document->id }},
                client_id: {{ $document->client_id }}
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

        addEvent(document, 'click', ".btn-save-document", (e) => {
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

            swalSaving();

            let data = {
                number: document.getElementById('number').value,
                document_date: document.getElementById('document_date').value,
                action_date: document.getElementById('action_date').value,
                document_status_id: document.getElementById('document_status_id').value,
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
                group_id: document.getElementById('group_id').value,
                job_date: document.getElementById('job_date') ? document.getElementById('job_date').value : null,
                start_time: document.getElementById('start_time') ? document.getElementById('start_time').value : null,
                end_time: document.getElementById('end_time') ? document.getElementById('end_time').value : null,
                will_call: document.getElementById('will_call') ? document.getElementById('will_call').value : null,
                next_date: document.getElementById('next_date') ? document.getElementById('next_date').value : null,
                stop_date: document.getElementById('stop_date') ? document.getElementById('stop_date').value : null,
                recurring_frequency: document.getElementById('recurring_frequency') ? document.getElementById('recurring_frequency').value : null,
                recurring_period: document.getElementById('recurring_period') ? document.getElementById('recurring_period').value : null,
            }
            axios.post('{{ route('documents.update', [$document->id]) }}', data).then(function () {
                axios.get('{{ route('documents.documentEdit.refreshEdit', [$document->id]) }}')
                    .then(response => {
                        setInnerHTML(document.getElementById('div-document-edit'), response.data)
                        //window.livewire.rescan(); //causing duplicate items on save
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
