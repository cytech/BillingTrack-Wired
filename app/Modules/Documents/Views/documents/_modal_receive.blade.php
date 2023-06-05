@include('documents._js_receive')
<div class="modal fade" id="receive-purchaseorder">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.receive_purchaseorder')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
                    <div class="table-responsive">
                        <table class="table items-list">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Item</th>
                                <th>Ordered Qty</th>
                                <th>Ordered Cost</th>
                                <th>Received Qty</th>
                                <th>Received Cost</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <input type="hidden" name="id" value="{!! $item->id !!}">
                                    <td>
                                        <span class="badge badge-{{ $item->rec_status_text }}"> {!! ucfirst($item->rec_status_text) !!}</span>
                                    </td>
                                    <td>{!! $item->name !!}</td>
                                    <td>{!! $item->quantity !!}</td>
                                    <td>{!! $item->price !!}</td>
                                    <td>{{ html()->text('rec_qty', $item->quantity - $item->rec_qty)->class('form-control') }}</td>
                                    <td>{{ html()->text('rec_cost', $item->price)->class('form-control') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="ms-3 form-check form-switch form-switch-md">
                            {{ html()->checkbox('itemrec', config('bt.updateProductsDefault'), 1)->class('form-check-input') }}
                            {{ html()->label(__('bt.update_products'), 'itemrec')->class('form-check-label fw-bold ps-3 pt-1') }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" id="purchaseorder-receive-confirm" class="btn btn-primary">@lang('bt.submit')
                </button>
            </div>
        </div>
    </div>
</div>
