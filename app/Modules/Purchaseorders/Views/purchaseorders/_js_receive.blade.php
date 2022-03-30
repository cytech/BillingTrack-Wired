<script type="text/javascript">
    ready(function () {
        const modal = bsModal('receive-purchaseorder')
        modal.show()
        document.getElementById('purchaseorder-receive-confirm').addEventListener('click', () => {
            const itemrec_ids = [];
            const itemrec_att = [];
            let itemrec = 0;

            document.querySelectorAll('.items-list tbody>tr').forEach((item) => {
                let id1, rec_qty, rec_cost;
                item.querySelectorAll('input').forEach((e) => {
                    if (e.name === 'id') {
                        itemrec_ids.push(e.value)
                        id1 = e.value
                    }
                    if (e.name === 'rec_qty') rec_qty = e.value
                    if (e.name === 'rec_cost') rec_cost = e.value
                });
                let row_arr = {
                    id: parseInt(id1),
                    rec_qty: parseFloat(rec_qty),
                    rec_cost: parseFloat(rec_cost)
                };
                itemrec_att.push(row_arr);
            })

            if (document.querySelector('input[name="itemrec"]').checked === true) {
                itemrec = 1;
            }

            axios.post('{{ route('purchaseorders.receive_items') }}', {
                user_id: document.getElementById('user_id').value,
                itemrec: itemrec,
                itemrec_ids: itemrec_ids,
                itemrec_att: itemrec_att,
            }).then(function (response) {
                setTimeout(function () { //give notify a chance to display before redirect
                    window.location = '{!! url('purchaseorders') !!}';
                }, 2000);
                notify('@lang('bt.items_successfully_received')', 'success');
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
    });
</script>
