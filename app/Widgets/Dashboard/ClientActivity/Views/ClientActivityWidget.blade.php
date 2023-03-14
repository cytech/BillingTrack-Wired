<div id="client-activity-widget">
    <section class="content">
        <div class="card ">
            <div class="card-header">
                <h5 class="text-bold mb-0">@lang('bt.recent_client_activity')</h5>
            </div>
            <div class="card-body p-3">
                {{--                <table class="table table-striped">--}}
                {{--                    <tbody>--}}
                {{--                    <tr>--}}
                {{--                        <th>@lang('bt.date')</th>--}}
                {{--                        <th>@lang('bt.activity')</th>--}}
                {{--                    </tr>--}}
                {{--                    @foreach ($recentClientActivity as $activity)--}}
                {{--                        <tr>--}}
                {{--                            <td>{{ $activity->formatted_created_at }}</td>--}}
                {{--                            <td>{!! $activity->formatted_activity !!}</td>--}}
                {{--                        </tr>--}}
                {{--                    @endforeach--}}
                {{--                    </tbody>--}}
                {{--                </table>--}}

                @foreach ($recentClientActivity as $activity)
                    <div class="row">
                        <div class="col-md-12">
                            <!-- The time line -->
                            <div class="timeline">
                                <!-- timeline time label -->
                                @foreach($activity as $actdate)
                                    @if($loop->first)
                                        <div class="time-label">
                                            <span class="text-bg-blue">{{ $actdate->formatted_created_at_date }}</span>
                                        </div>
                                    @endif
                                    <!-- /.timeline-label -->
                                    <!-- timeline item -->
                                    <div>
                                        <i class="timeline-icon fa-solid fa-wave-square text-bg-primary"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa-solid fa-clock"></i>{{ $actdate->formatted_created_at_time }}</span>
                                            {{--                                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>--}}
                                            <h6 class="timeline-header">{!! $actdate->formatted_activity !!}</h6>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- END timeline item -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
