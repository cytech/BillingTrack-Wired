<nav class="main-header navbar navbar-expand navbar-{{ $headClass }} bg-{{ $headBackground }} border-bottom">
    <div class="container-fluid">
        @push('scripts')
            <script>
                //need to connect up scss bs4 themes...
                //style datatable header and btn-primary like the navbar
                const top_bar = document.querySelector('.bg-{{ $headBackground }}');
                const bg = getComputedStyle(top_bar).backgroundColor;
                let color = '#FFFFFF';
                // override white yellow and light gray color to black
                if (bg === 'rgb(255, 255, 255)' || bg === 'rgb(255, 237, 74)' || bg === 'rgb(242, 244, 245)') {
                    color = '#000000';
                }

                const newStyles = document.createElement('style');
                document.head.append(newStyles);
                newStyles.innerHTML = ".btn-primary, .table.dataTable thead > tr > th {background-color: "
                    + bg + " !important; color: " + color + " !important;}";
            </script>
        @endpush
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('session.logout') }}"><i
                            class="fa fa-power-off"></i></a></li>
        </ul>
    </div>
</nav>

