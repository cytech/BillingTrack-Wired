<script type="text/javascript">
    function refreshTaskList() {
        loadModal('{{ route('timeTracking.projects.refreshTaskList') }}', {
            project_id: {{ $project->id }}
        }, 'project-task-list')
    }
</script>
