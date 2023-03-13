<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <a class="dropdown-item" href="{{ route('clients.show', [$model->id]) }}"
        ><i class="fa fa-search"></i> @lang('bt.view')</a>
        <a class="dropdown-item" href="{{ route('clients.edit', [$model->id]) }}"
        ><i class="fa fa-edit"></i> @lang('bt.edit')</a>
        <a class="dropdown-item" href="#" id="btn-create-quote"
           {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
           onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($model->quotes()->getRelated())) }}', 'create', {{ $model->id }}, null, true)">
            <i class="far fa-file-alt"></i> @lang('bt.create_quote')</a>
        <a class="dropdown-item" href="#" id="btn-create-workorder"
           {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
           onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($model->workorders()->getRelated())) }}', 'create', {{$model->id}}, null, true)">
            <i class="far fa-file-alt"></i> @lang('bt.create_workorder')</a>
        <a class="dropdown-item" href="#" id="btn-create-invoice"
           {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
           onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($model->invoices()->getRelated())) }}', 'create', {{$model->id}}, null, true)">
            <i class="far fa-file-alt"></i> @lang('bt.create_invoice')</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.trash_client_warning')', '@lang('bt.trash_client_warning_msg')', '{{ route('clients.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
