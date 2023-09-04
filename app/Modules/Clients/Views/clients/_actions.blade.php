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
           {{--                    params 3 thru ... mount($modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null, $lineitem = null)--}}
           onclick="window.Livewire.dispatch('showModal', {alias: 'modals.create-module-modal', params: { modulefullname: '{{ addslashes(get_class($model->quotes()->getRelated())) }}', module_type: 'Quote', moduleop: 'create', resource_id: {{ $model->id }}, module_id: null, readonly: true }})">
            <i class="far fa-file-alt"></i> @lang('bt.create_quote')</a>
        <a class="dropdown-item" href="#" id="btn-create-workorder"
           {{--                    params 3 thru ... mount($modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null, $lineitem = null)--}}
           onclick="window.Livewire.dispatch('showModal', {alias: 'modals.create-module-modal', params: { modulefullname: '{{ addslashes(get_class($model->workorders()->getRelated())) }}', module_type: 'Workorder', moduleop: 'create', resource_id: {{ $model->id }}, module_id: null, readonly: true }})">
            <i class="far fa-file-alt"></i> @lang('bt.create_workorder')</a>
        <a class="dropdown-item" href="#" id="btn-create-invoice"
           {{--                    params 3 thru ... mount($modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null, $lineitem = null)--}}
           onclick="window.Livewire.dispatch('showModal', {alias: 'modals.create-module-modal', params: { modulefullname: '{{ addslashes(get_class($model->invoices()->getRelated())) }}', module_type: 'Invoice', moduleop: 'create', resource_id: {{ $model->id }}, module_id: null, readonly: true }})">
            <i class="far fa-file-alt"></i> @lang('bt.create_invoice')</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.trash_client_warning')', '@lang('bt.trash_client_warning_msg')', '{{ route('clients.delete', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
