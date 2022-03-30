<script type="text/javascript">
    ready(function () {
        addEvent(document, 'click', "#btn-create-contact-note", (e) => {
            if (document.getElementById('note_content_contact').value !== '') {
                @if (isset($showPrivateCheckbox) and $showPrivateCheckbox == true)
                    showPrivateCheckbox = 1;
                if (document.getElementById("private").checked === true) {
                    isPrivate = 1;
                } else {
                    isPrivate = 0;
                }
                @else
                    showPrivateCheckbox = 0;
                isPrivate = 0;
                @endif
                axios.post('{{ route('notes.create') }}', {
                    model: '{{ addslashes($model) }}',
                    model_id: {{ $object->id }},
                    note: document.getElementById('note_content_contact').value,
                    isPrivate: isPrivate,
                    showPrivateCheckbox: showPrivateCheckbox
                }).then(function (response) {
                    document.getElementById('note_content_contact').value = '';
                    if (isPrivate) document.getElementById("private").checked = false
                    document.getElementById('notes-list-contact').innerHTML = response.data;
                });
            }
        });

        @if (!auth()->user()->client_id)
        addEvent(document, 'click', ".delete-note", (e) => {
            noteId = e.target.dataset.noteId
            document.getElementById('note-' + noteId).style.display = 'none'
            axios.post("{{ route('notes.delete') }}", {
                id: noteId
            });
        });
        @endif
    });
</script>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-solid direct-chat direct-chat-warning">
            @if (!isset($hideHeader))
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.notes')</h3>
                </div>
            @endif
            <div class="card-body">
                <div class="direct-chat-messages" id="notes-list-contact">
                    @include('notes._notes_list')
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12">
                        @if (isset($showPrivateCheckbox) and $showPrivateCheckbox == true)
                            <label>
                                <input type="checkbox" name="private" id="private"> @lang('bt.private')
                            </label>
                        @endif
                        <textarea placeholder="@lang('bt.placeholder_type_message')"
                                  class="form-control"
                                  id="note_content_contact"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary btn-flat"
                                id="btn-create-contact-note">@lang('bt.add_note')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
