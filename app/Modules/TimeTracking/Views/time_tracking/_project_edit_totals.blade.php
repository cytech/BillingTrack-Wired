<div class="card card-outline card-primary">
    <div class="card-body">
        <span class="float-start"><strong>@lang('bt.unbilled_hours')</strong></span>
        <span class="float-end">{{ $project->unbilled_hours }}</span>
        <div class="clearfix"></div>
        <span class="float-start"><strong>@lang('bt.billed_hours')</strong></span>
        <span class="float-end">{{ $project->billed_hours }}</span>
        <div class="clearfix"></div>
        <span class="float-start"><strong>@lang('bt.total_hours')</strong></span>
        <span class="float-end">{{ $project->hours }}</span>
        <div class="clearfix"></div>
    </div>
</div>
