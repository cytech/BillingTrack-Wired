@if (!config('app.demo'))
    <script type="text/javascript">
        ready(function () {
            const modal = bsModal('modal-attach-files')
            modal.show()
            addEvent(document, 'change', '#input-attachments', (e) => {
                formData = new FormData(document.forms.namedItem('form-attachments'));
                formData.append('model', '{{ addslashes($model) }}');
                formData.append('model_id', '{{ $modelId }}');

                document.getElementById("input-attachments").setAttribute('disabled', 'disabled');
                resetProgressBar('0%', '0%');
                document.getElementById('attachment-upload-progress').style.display = 'block'

                axios.post('{{ route('attachments.ajax.upload') }}', formData, {
                        onUploadProgress: function (progressEvent) {
                            progress(progressEvent)
                        }
                    }
                ).then(function (response) {
                    document.getElementById('input-attachments').value = ''
                    loadModal('{{ route('attachments.ajax.list') }} ', {
                        model: '{{ addslashes($model) }}',
                        model_id: '{{ $modelId }}'
                    }, 'attachments-list')

                    document.getElementById('input-attachments').removeAttribute('disabled')
                    modal.hide();
                }).catch(function (error) {
                    let aupb = document.getElementById('attachment-upload-progress-bar')
                    aupb.classList.add('bg-danger')
                    aupb.innerHTML = error
                    document.getElementById('input-attachments').removeAttribute('disabled')
                })
            })

            function progress(e) {
                if (e.lengthComputable) {
                    const max = e.total;
                    const current = e.loaded;
                    const Percentage = Math.round((current * 100) / max);
                    let aupb = document.getElementById("attachment-upload-progress-bar")
                    aupb.style.width = Percentage + '%'
                    aupb.innerHTML = Percentage + '%'

                    if (Percentage === 100) {
                        resetProgressBar('100%', '@lang('bt.complete')');
                        aupb.classList.add('bg-success')
                        aupb.innerHTML = '@lang('bt.complete')'
                    }
                }
            }

            function resetProgressBar(width, text) {
                let aupb = document.getElementById("attachment-upload-progress-bar")
                aupb.classList.remove("bg-danger", "bg-success")
                aupb.style.width = width
                aupb.innerHTML = text;
            }
        });
    </script>

    <div class="modal fade" id="modal-attach-files">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('bt.attach_files')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">@lang('bt.attach_files')</p>
                    <form method="post" enctype="multipart/form-data" name="form-attachments" id="form-attachments"
                          style="margin-bottom: 10px;">
                        <div class="mb-3">
                            <input class="form-control" type="file" name="attachments[]" id="input-attachments"
                                   multiple>
                        </div>
                    </form>
                    <div style="display: none;" id="attachment-upload-progress">
                        <p class="fw-bold">@lang('bt.upload_progress')</p>
                        <div class="progress">
                            <div id="attachment-upload-progress-bar" class="progress-bar" role="progressbar"
                                 style="width: 0;">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
