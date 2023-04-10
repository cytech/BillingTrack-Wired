@include('documents._js_edit_to')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">@lang('bt.to')</h3>
        <div class="card-tools float-end">
            @if($document->module_type != 'Purchaseorder')

                <button class="btn btn-secondary btn-sm"
                        {{--                                     params 3 thru ...> mount(,,$modulefullname, $module_id = null, $search_type)--}}
                        onclick="window.livewire.emit('showModal', 'modals.search-modal', '{{  addslashes(get_class($document)) }}', {{$document->id}}, 'client')"
                ><i class="fa fa-exchange-alt"></i> @lang('bt.change')</button>
                <button class="btn btn-secondary btn-sm" id="btn-edit-client"
                        data-client-id="{{ $document->client_id }}"><i
                            class="fa fa-pencil-alt"></i> @lang('bt.edit')</button>
            @else
                <button class="btn btn-secondary btn-sm"
                        {{--                                     params 3 thru ...> mount(,,$modulefullname, $module_id = null, $search_type)--}}
                        onclick="window.livewire.emit('showModal', 'modals.search-modal', '{{  addslashes(get_class($document)) }}', {{$document->id}}, 'vendor')"
                ><i class="fa fa-exchange-alt"></i> @lang('bt.change')</button>
                <button class="btn btn-secondary btn-sm" id="btn-edit-vendor"
                        data-vendor-id="{{ $document->client_id }}"><i
                            class="fa fa-pencil-alt"></i> @lang('bt.edit')</button>

            @endif
        </div>
    </div>
    @if($document->module_type != 'Purchaseorder')
        <div class="card-body">
            <strong>{{ $document->client->name }}</strong><br>
            {!! $document->client->formatted_address !!}<br>
            @lang('bt.phone'): {{ $document->client->phone }}<br>
            @lang('bt.email'): {{ $document->client->email }}
        </div>
    @else
        <div class="card-body">
            <strong>{{ $document->vendor->name }}</strong><br>
            {!! $document->vendor->formatted_address !!}<br>
            @lang('bt.phone'): {{ $document->vendor->phone }}<br>
            @lang('bt.email'): {{ $document->vendor->email }}
        </div>
    @endif
</div>