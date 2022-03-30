<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-contact')
        modal.show()
        document.getElementById('btn-contact-submit').addEventListener('click', (e) => {
            let data = {
                client_id: {{ $clientId }},
                first_name: document.getElementById('contact_first_name').value,
                last_name: document.getElementById('contact_last_name').value,
                name: document.getElementById('contact_name').value,
                title_id: document.getElementById('contact_title_id').value,
                is_primary: document.getElementById('contact_is_primary').value,
                optin: document.getElementById('contact_optin').value,
                phone: document.getElementById('contact_phone').value,
                fax: document.getElementById('contact_fax').value,
                mobile: document.getElementById('contact_mobile').value,
                email: document.getElementById('contact_email').value
            }
            axios.post("{{ $submitRoute }}", data).then(function (response) {
                modal.hide()
                document.getElementById('tab-contacts').innerHTML = response.data
            }).catch(function (error) {
                showErrors(error.response.data.errors);
            });
        });
    });
</script>
<div class="modal fade" id="modal-contact">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @if ($editMode)
                        @lang('bt.edit_contact')
                    @else
                        @lang('bt.add_contact')
                    @endif
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.first_name'):</label>
                                {!! Form::text('contact_first_name', ($editMode) ? $contact->first_name : null, ['class' => 'form-control', 'id' => 'contact_first_name']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.last_name'):</label>
                                {!! Form::text('contact_last_name', ($editMode) ? $contact->last_name : null, ['class' => 'form-control', 'id' => 'contact_last_name']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.name'):</label>
                                {!! Form::text('contact_name', ($editMode) ? $contact->name : null, ['class' => 'form-control', 'id' => 'contact_name']) !!}
                            </div>
                        </div>
                        <script>
                            addEvent(document, 'change', '#contact_last_name', (e) => {
                                let fullnameArray = [document.getElementById('contact_first_name').value, document.getElementById('contact_last_name').value];
                                if (!document.getElementById('contact_name').value)
                                    document.getElementById('contact_name').value = fullnameArray.join(' ')
                            });
                        </script>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.title'):</label>
                                {!! Form::select('contact_title_id', $titles, ($editMode) ? $contact->title_id : 1 , ['id' => 'contact_title_id', 'class' => 'form-select']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.is_primary'):</label>
                                {!! Form::select('contact_is_primary', ['0' => __('bt.no'), '1' => __('bt.yes')], ($editMode) ? $contact->is_primary : 0 , ['id' => 'contact_is_primary', 'class' => 'form-select']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>@lang('bt.optin'):</label>
                                {!! Form::select('contact_optin', ['0' => __('bt.no'), '1' => __('bt.yes')], ($editMode) ? $contact->optin : 1, ['id' => 'contact_optin', 'class' => 'form-select']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>@lang('bt.phone_number'): </label>
                                {!! Form::text('contact_phone', ($editMode) ? $contact->phone : null, ['id' => 'contact_phone', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>@lang('bt.fax_number'): </label>
                                {!! Form::text('contact_fax', ($editMode) ? $contact->fax : null, ['id' => 'contact_fax', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>@lang('bt.mobile_number'): </label>
                                {!! Form::text('contact_mobile', ($editMode) ? $contact->mobile : null, ['id' => 'contact_mobile', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>@lang('bt.email'):</label>
                                {!! Form::text('contact_email', ($editMode) ? $contact->email : null, ['class' => 'form-control', 'id' => 'contact_email']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($editMode)
                                @include('notes._notes_contact', ['object' => $contact, 'model' => 'BT\Modules\Clients\Models\Contact', 'hideHeader' => true])
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fa fa-times-circle"></i> @lang('bt.cancel')</button>
                <button type="button" id="btn-contact-submit" class="btn btn-primary"><i
                            class="fa fa-save"></i> @lang('bt.save')</button>
            </div>
        </div>
    </div>
</div>
