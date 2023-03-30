<script type="text/javascript">
    ready(function () {

        autosize(document.querySelectorAll('textarea'))

        document.getElementById('btn-quote-to-invoice').addEventListener('click', () => {
            loadModal('{{ route('quoteToInvoice.create') }}', {
                quote_id: {{ $quote->id }},
                client_id: {{ $quote->client_id }}
            })
        });

        document.getElementById('btn-quote-to-workorder').addEventListener('click', () => {
            loadModal('{{ route('quoteToWorkorder.create') }}', {
                quote_id: {{ $quote->id }},
                client_id: {{ $quote->client_id }}
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

        addEvent(document, 'click', ".btn-save-quote", (e) => {
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
                quote_date: document.getElementById('quote_date').value,
                expires_at: document.getElementById('expires_at').value,
                quote_status_id: document.getElementById('quote_status_id').value,
                items: items,
                terms: document.getElementById('terms').value,
                footer: document.getElementById('footer').value,
                currency_code: document.getElementById('currency_code').value,
                exchange_rate: document.getElementById('exchange_rate').value,
                custom: custom_fields,
                apply_exchange_rate: apply_exchange_rate,
                template: document.getElementById('template').value,
                summary: document.getElementById('summary').value,
                discount: document.getElementById('discount').value
            }
            axios.post('{{ route('quotes.update', [$quote->id]) }}', data).then(function () {
                axios.get('{{ route('quotes.quoteEdit.refreshEdit', [$quote->id]) }}')
                    .then(response => {
                        setInnerHTML(document.getElementById('div-quote-edit'), response.data)
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
