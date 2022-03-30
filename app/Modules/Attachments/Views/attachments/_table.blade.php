<script type="text/javascript">
    ready(function () {
        let delbtns = document.querySelectorAll('.btn-delete-attachment')
        delbtns.forEach(function (delbtn) {
            delbtn.addEventListener('click', function (e) {
                let attachmentId = e.target.dataset.attachmentId
                deleteAttachment(attachmentId)
            })
        })

        function deleteAttachment(attachmentId) {
            Swal.fire({
                title: '@lang('bt.trash_record_warning')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d68500',
                confirmButtonText: '@lang('bt.yes_sure')'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ route('attachments.ajax.delete') }}', {
                        model: '{{ addslashes($model) }}',
                        model_id: '{{ $object->id }}',
                        attachment_id: attachmentId
                    }).then(function () {
                        loadModal('{{ route('attachments.ajax.list') }} ', {
                            model: '{{ addslashes($model) }}',
                            model_id: '{{ $object->id }}'
                        }, 'attachments-list')
                    }).catch(function (error) {
                        console.log(error.response)
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    //
                }
            });
        }

        let visbtns = document.querySelectorAll('.client-visibility')
        visbtns.forEach(function (visbtn) {
            visbtn.addEventListener('change', function (e) {
                axios.post('{{ route('attachments.ajax.access.update') }}', {
                    client_visibility: e.target.value,
                    attachment_id: e.target.dataset.attachmentId
                });
            })
        })

        document.getElementById('btn-attach-files').addEventListener('click', function () {
            loadModal('{{ route('attachments.ajax.modal') }} ', {
                model: '{{ addslashes($model) }}',
                model_id: '{{ $object->id }}'
            })
        })
    });
</script>
<div id="attachments-list">
    @if (!config('app.demo'))
        <button class="btn btn-primary btn-sm" type="button" id="btn-attach-files">@lang('bt.attach_files')</button>
    @else
        <button class="btn btn-primary btn-sm" type="button">File attachments are disabled in the demo</button>
    @endif
    <table class="table table-sm">
        <thead>
        <tr>
            <th>@lang('bt.attachment')</th>
            <th>@lang('bt.client_visibility')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($object->attachments()->orderBy('created_at', 'desc')->get() as $attachment)
            <tr>
                <td><a href="{{ $attachment->download_url }}">{{ $attachment->filename }}</a></td>
                <td>
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::select('', $object->attachment_permission_options, $attachment->client_visibility, ['class' => 'form-control client-visibility', 'data-attachment-id' => $attachment->id]) !!}
                        </div>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-delete-attachment" title="@lang('bt.trash')"
                            data-attachment-id="{{ $attachment->id }}">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
