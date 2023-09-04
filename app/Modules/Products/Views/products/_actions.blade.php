<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href="{{ route('products.edit', [$model->id]) }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href="#" id="btn-create-purchaseorder"
            {{--                    params 3 thru ... mount($modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null, $lineitem = null)--}}
            onclick="window.Livewire.dispatch('showModal', {alias: 'modals.create-module-modal', params: { modulefullname: '{{ addslashes(get_class($model->purchaseorders()->getRelated())) }}', module_type: 'Purchaseorder', moduleop: 'create', resource_id: {{ $model->vendor_id ?? 'null'  }}, module_id: null, readonly: true, lineitem: {{ $model->id}} }})">
            <i class="far fa-file-alt"></i> @lang('bt.create_purchaseorder')</a>
    </div>
</div>
