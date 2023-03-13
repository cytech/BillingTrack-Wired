<div id="quote-dashboard-totals-widget">
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', ".quote-dashboard-total-change-option", (e) => {
                var option = e.target.dataset.id

                axios.post("{{ route('widgets.dashboard.quoteSummary.renderPartial') }}", {
                    widgetQuoteSummaryDashboardTotals: option,
                    widgetQuoteSummaryDashboardTotalsFromDate: document.getElementById('quote-dashboard-total-setting-from-date').value,
                    widgetQuoteSummaryDashboardTotalsToDate: document.getElementById('quote-dashboard-total-setting-to-date').value
                }).then(function (response) {
                    setInnerHTML(document.getElementById('quote-dashboard-totals-widget'), response.data)
                })
            });
        });
    </script>
    <div class="card">
        <div class="card-header align-content-center">
            <h5 class="text-bold mb-0 float-start">@lang('bt.quote_summary')</h5>
            <div class="card-tools float-end">
                <div class="btn-group">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-calendar"></i> {{ $quoteDashboardTotalOptions[config('bt.widgetQuoteSummaryDashboardTotals')] }}
                        </button>
                        <div class="dropdown-menu" role="menu">
                            @foreach ($quoteDashboardTotalOptions as $key => $option)
                                @if ($key != 'custom_date_range')
                                    <a href="#" onclick="return false;"
                                       class="quote-dashboard-total-change-option dropdown-item"
                                       data-id="{{ $key }}">{{ $option }}</a>
                                @else
                                    <a href="#" onclick="return false;" data-bs-toggle="modal"
                                       data-bs-target="#quote-summary-widget-modal" class="dropdown-item">{{ $option }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="btn btn-sm"
                        type="button"
                        {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                        onclick="window.livewire.emit('showModal', 'modals.create-module-modal',  'BT\\Modules\\Quotes\\Models\\Quote', 'create' )"
                ><i class="fa fa-plus"></i> @lang('bt.create_quote')
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-purple ">
                        <div class="inner">
                            <h4 class="text-bold">{{ $quotesTotalDraft }}</h4>
                            <p>@lang('bt.draft_quotes')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-pencil-alt"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=draft">
                            @lang('bt.view_draft_quotes') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-green">
                        <div class="inner">
                            <h4 class="text-bold">{{ $quotesTotalSent }}</h4>
                            <p>@lang('bt.sent_quotes')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-share-square"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=sent">
                            @lang('bt.view_sent_quotes') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-orange">
                        <div class="inner">
                            <h4 class="text-bold">{{ $quotesTotalRejected }}</h4>
                            <p>@lang('bt.rejected_quotes')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-thumbs-down"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=rejected">
                            @lang('bt.view_rejected_quotes') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-blue">
                        <div class="inner">
                            <h4 class="text-bold">{{ $quotesTotalApproved }}</h4>
                            <p>@lang('bt.approved_quotes')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-thumbs-up"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=approved">
                            @lang('bt.view_approved_quotes') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="quote-summary-widget-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('bt.custom_date_range')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('bt.from_date'):</label>
                        <x-fp_common
                                name="setting_widgetQuoteSummaryDashboardTotalsFromDate"
                                id="quote-dashboard-total-setting-from-date"
                                class="form-control"
                                value="{{config('bt.widgetQuoteSummaryDashboardTotalsFromDate')}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('bt.to_date'):</label>
                        <x-fp_common
                                name="setting_widgetQuoteSummaryDashboardTotalsToDate"
                                id="quote-dashboard-total-setting-to-date"
                                class="form-control"
                                value="{{config('bt.widgetQuoteSummaryDashboardTotalsToDate')}}"></x-fp_common>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                    <button type="button" class="btn btn-primary quote-dashboard-total-change-option"
                            data-id="custom_date_range" data-bs-dismiss="modal">@lang('bt.save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
