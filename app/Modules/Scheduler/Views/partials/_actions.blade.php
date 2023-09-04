<div class="btn-group position-static">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
        @lang('bt.options')
    </button>
    <div class="dropdown-menu dropdown-menu-end" role="menu">
        <button class="dropdown-item "
                type="button"
                {{--                    params 3 thru ... mount($module = null, $fromcalendar = null, $date = null)--}}
                onclick="window.Livewire.dispatch('showModal', {alias: 'modals.create-event-modal', params: { module: {!! $model !!} }})"
        ><i class="fa fa-edit"></i> @lang('bt.edit')
        </button>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#"
           onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('scheduler.trashevent', [$model->id]) }}');"><i
                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
    </div>
</div>
