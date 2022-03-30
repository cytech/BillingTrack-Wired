<script type="text/javascript">
    function refreshTotals() {
        loadModal('{{ route('timeTracking.projects.refreshTotals') }}', {
            project_id: {{ $project->id }}
        }, 'div-totals')
    }
</script>
