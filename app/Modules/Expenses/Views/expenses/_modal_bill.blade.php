<script type="text/javascript">
    ready(function () {
        const modal = bsModal('create-expense-bill')
        modal.show()
        addEvent(document, 'change', ".add-line-item", (e) => {
            let lio = document.getElementById('line-item-options')
            if (e.target.id === 'add-line-item') {
                lio.style.display = 'block'
            } else {
                lio.style.display = 'none'
            }
        })

        addEvent(document, 'click', "#btn-create-expense-bill-confirm", (e) => {
            axios.post("{{ route('expenses.expenseBill.store') }}", {
                id: {{ $expense->id }},
                invoice_id: document.getElementById('invoice_id').value,
                item_name: document.getElementById('item_name').value,
                item_description: document.getElementById('item_description').value,
                add_line_item: document.querySelector('input[name=add_line_item]:checked').value
            }).then(function () {
                window.location = '{{ $redirectTo }}';
            }).catch(function (error) {
                showErrors(error.response.data.errors);
            });
        })
    });
</script>
<div class="modal fade" id="create-expense-bill">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.bill_this_expense')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
                    @if ($invoices)
                        <div class="mb-3">
                            <label class="col-form-label">* @lang('bt.label_invoice'):</label>
                            {!! Form::select('invoice_id', $invoices, null, ['id' => 'invoice_id', 'class' => 'form-control']) !!}
                        </div>

                        <div class="mb-3">
                            <label class="col-form-label">{!! Form::radio('add_line_item', 1, true, ['class' => 'add-line-item', 'id' => 'add-line-item']) !!} @lang('bt.add_line_item_to_invoice')</label><br>
                            <label class="col-form-label">{!! Form::radio('add_line_item', 0, false, ['class' => 'add-line-item', 'id' => 'no-add-line-item']) !!} @lang('bt.do_not_add_line_item_to_invoice')</label>
                        </div>

                        <div id="line-item-options">
                            <div class="mb-3">
                                <label class="col-form-label">* @lang('bt.label_item_name'):</label>
                                {!! Form::text('item_name', $expense->category->name, ['id' => 'item_name', 'class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">@lang('bt.label_item_description'):</label>
                                {!! Form::textarea('item_description', $expense->description, ['id' => 'item_description', 'rows' => '3', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    @else
                        @lang('bt.client'): {{ $expense->client->name }}
                        <p>@lang('bt.no_open_invoices')</p>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                @if ($invoices)
                    <button type="button" id="btn-create-expense-bill-confirm"
                            class="btn btn-primary">@lang('bt.submit')</button>
                @endif
            </div>
        </div>
    </div>
</div>
