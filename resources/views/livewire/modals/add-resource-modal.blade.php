<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">@lang('bt.add_items_from', ['resource_type' => $resource_type . 's'])</h4>
            <div class="float-right">
                <button type="button" class="btn btn-secondary" wire:click.prevent="doCancel()"
                        data-bs-dismiss="modal">
                    @lang('bt.cancel')
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click.prevent="addItems()">@lang('bt.submit')</button>
            </div>
        </div>
        @if(class_basename($module) == 'Purchaseorder')
            <div class="modal-header pt-2 pb-1">
                <label>
                    <input wire:model="pref_vendor" type="checkbox" checked name="pref_vendor"
                           id="pref_vendor"> @lang('bt.vendor_preferred_only', ['vname' => $module->vendor->name])
                </label>
            </div>
        @endif
        <div class="modal-body">
            <div id="modal-status-placeholder"></div>
            <table class="table table-bordered table-striped" id="resource-table">
                <thead>
                <tr class="prodheader">
                    <th></th>
                    <th>@lang('bt.name')</th>
                    <th>@lang('bt.description')</th>
                    @if($resource_type == 'Product')
                        <th>@lang('bt.product_numstock')</th>
                    @endif
                    <th>@lang('bt.price')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($resources as $resource)
                    @if(class_basename($resource) == 'Employee')
                        <tr class="prodlist">
                            <td><input wire:model.defer="selected_resources" type="checkbox" name="resource_ids[]"
                                       value="{!! $resource->id!!}"></td>
                            @if($resource->driver)
                                <td style="color: blue">{!!  $resource->name ?? $resource->short_name !!}</td>
                            @else
                                <td>{!!  $resource->name ?? $resource->short_name !!}</td>
                            @endif
                            <td>{!!  $resource->description ?? $resource->title !!}</td>
                            <td>{!!  $resource->formatted_cost ?? $resource->formatted_price ?? $resource->formatted_billing_rate !!}</td>
                        </tr>
                    @else
                        <tr class="prodlist">
                            <td><input wire:model.defer="selected_resources" type="checkbox" name="resource_ids[]"
                                       value="{!! $resource->id!!}"></td>
                            <td>{!!  $resource->name ?? $resource->short_name !!}</td>
                            <td>{!!  $resource->description ?? $resource->title !!}</td>
                            @if($resource_type == 'Product')
                                <td @if($resource->is_trackable) class="bg-secondary"
                                    title="@lang('bt.trackable')" @endif>{!!  \BT\Support\NumberFormatter::format($resource->numstock,null,2) ?? 0 !!}
                            @endif
                            @if(class_basename($module) == 'Purchaseorder')
                                <td>{!!  $resource->formatted_cost  !!}</td>
                            @else
                                <td>{!!  $resource->formatted_price ?? $resource->formatted_billing_rate !!}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

