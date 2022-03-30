<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-bill-task')
        modal.show()
        let howToBill = document.getElementById('how_to_bill').value
        const invoiceCount = '{{ $invoiceCount }}';

        billOptions(howToBill, invoiceCount);

        addEvent(document, 'change', "#how_to_bill", (e) => {
            howToBill = e.target.value
            billOptions(howToBill, invoiceCount);
        })

        addEvent(document, 'click', "#btn-submit-bill", (e) => {
            axios.post("{{ route('timeTracking.bill.store') }}", {
                how_to_bill: howToBill,
                project_id: {{ $project->id }},
                group_id: document.getElementById('group_id').value,
                invoice_id: document.getElementById('invoice_id').value,
                task_ids: JSON.stringify({{ $taskIds }})
            }).then(function (response) {
                window.location = response.data;
            });
        })

        function billOptions(howToBill, invoiceCount) {
            document.getElementById('div-bill-new').style.display = 'none'
            document.getElementById('div-bill-existing').style.display = 'none'
            document.getElementById('div-bill-' + howToBill).style.display = 'block'

            if (howToBill === 'existing' && invoiceCount === 0) {
                document.getElementById('btn-submit-bill').classList.add('disabled')
            } else {
                document.getElementById('btn-submit-bill').classList.remove('disabled')
            }
        }
    });
</script>
<div class="modal fade" id="modal-bill-task">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.how_to_bill')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    {!! Form::select('how_to_bill', ['new' => trans('bt.bill_to_new_invoice'), 'existing' => trans('bt.bill_to_existing_invoice')], null, ['id' => 'how_to_bill', 'class' => 'form-control']) !!}
                </div>
                <div id="div-bill-new" style="display: none;">
                    <div class="mb-3">
                        <label>@lang('bt.group'):</label>
                        {!! Form::select('group_id', $groups, config('bt.invoiceGroup'),
                        ['id' => 'group_id', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div id="div-bill-existing" style="display: none;">
                    <div class="mb-3">
                        <label>@lang('bt.choose_invoice_to_bill'):</label>
                        {!! Form::select('invoice_id', $invoices, null, ['class' => 'form-control', 'id' => 'invoice_id']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" class="btn btn-primary" id="btn-submit-bill">@lang('bt.submit')</button>
            </div>
        </div>
    </div>
</div>
