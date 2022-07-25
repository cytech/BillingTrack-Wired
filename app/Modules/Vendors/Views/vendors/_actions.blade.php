<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href="{{ route('vendors.show', [$model->id]) }}" id="view-vendor-{{ $model->id }}"><i
                    class="fa fa-search"></i> @lang('bt.view')</a>
        <a class="dropdown-item" href="{{ route('vendors.edit', [$model->id]) }}" id="edit-vendor-{{ $model->id }}"><i
                    class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href="#" id="btn-create-purchaseorder"
           {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
           onclick="window.livewire.emit('showModal', 'modals.create-module-modal',
                   '{{ addslashes(get_class($model->purchaseorders()->getRelated())) }}', 'create', {{ $model->id }}, null, true)">
            <i class="far fa-file-alt"></i> @lang('bt.create_purchaseorder')</a>
    </div>
</div>
