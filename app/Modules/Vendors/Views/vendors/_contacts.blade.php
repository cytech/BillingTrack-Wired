<script type="text/javascript">
    ready(function () {
        document.querySelector('#tab-contacts').addEventListener('click', ({target}) => {
            if (target.matches('.btn-edit-contact')) {
                loadModal(target.dataset.url)
            } else if (target.matches('.btn-delete-contact')) {
                Swal.fire({
                    title: '@lang('bt.trash_record_warning')',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d68500',
                    confirmButtonText: '@lang('bt.yes_sure')'
                }).then((result) => {
                    if (result.value) {
                        axios.post('{{ route('vendors.contacts.delete', [$vendorId]) }}', {
                            id: target.dataset.contactId,
                        }).then(function (response) {
                            document.getElementById('tab-contacts').innerHTML = response.data
                        })
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        //
                    }
                });
            }
        })

        addEvent(document, 'click', '.update-default', (e) => {
            axios.post('{{ route('vendors.contacts.updateDefault', [$vendorId]) }}', {
                id: e.target.dataset.contactId,
                default: e.target.dataset.default
            }).then(function (response) {
                document.getElementById('tab-contacts').innerHTML = response.data
            });
        });
    });
</script>
@include('layouts._alerts')
<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>@lang('bt.name')</th>
                <th>@lang('bt.title')</th>
                <th>@lang('bt.phone')</th>
                <th>@lang('bt.fax')</th>
                <th>@lang('bt.mobile')</th>
                <th>@lang('bt.email')</th>
                <th>@lang('bt.default_to')</th>
                <th>@lang('bt.default_cc')</th>
                <th>@lang('bt.default_bcc')</th>
                <th>@lang('bt.is_primary')</th>
                <th>@lang('bt.optin')</th>
                <th>@lang('bt.options')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->title->name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->fax }}</td>
                    <td>{{ $contact->mobile }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>
                        <button class="btn btn-link update-default" data-default="to"
                                data-contact-id="{{ $contact->id }}">{{ $contact->formatted_default_to }}</button>
                    </td>
                    <td>
                        <button class="btn btn-link update-default" data-default="cc"
                                data-contact-id="{{ $contact->id }}">{{ $contact->formatted_default_cc }}</button>
                    </td>
                    <td>
                        <button class="btn btn-link update-default" data-default="bcc"
                                data-contact-id="{{ $contact->id }}">{{ $contact->formatted_default_bcc }}</button>
                    </td>
                    <td>{{ $contact->formatted_is_primary }}</td>
                    <td>{{ $contact->formatted_optin }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-toggle="dropdown">
                                @lang('bt.options')
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <button class="btn btn-link btn-edit-contact dropdown-item"
                                        data-url="{{ route('vendors.contacts.edit', [$vendorId, $contact->id]) }}"><i
                                            class="fa fa-edit"></i> @lang('bt.edit')</button>
                                <div class="dropdown-divider"></div>
                                <button class="btn btn-link btn-delete-contact dropdown-item"
                                        data-contact-id={{ $contact->id }}><i
                                            class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
