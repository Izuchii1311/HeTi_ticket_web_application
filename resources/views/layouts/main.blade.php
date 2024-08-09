<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', "HeTi - Home")</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Themesbrand" name="author" />

    {{-- Logo --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/HeTi_Logo.png') }}">

    <link href="{{asset("assets")}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets")}}/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="{{asset("assets")}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets')}}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- My Style --}}
    @stack('style')
</head>

<body data-topbar="dark" data-layout="horizontal">
    <div id="layout-wrapper">
        <div class='row'>
            @include('layouts.partials.header')
        </div>

        @include('layouts.partials.navbar')

        <div class="main-content">
            @yield('content')

            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <script src="{{asset("assets")}}/libs/jquery/jquery.min.js"></script>
    <script src="{{asset("assets")}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset("assets")}}/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{asset("assets")}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{asset("assets")}}/libs/node-waves/waves.min.js"></script>

    <!-- Required datatable js -->
    <script src="{{asset('assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- Buttons examples -->
    <script src="{{asset('assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="{{asset("assets")}}/js/app.js"></script>
    <script src="{{asset('assets')}}/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- My Script --}}
    @stack('js')

    <script>
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        @if(Session::has('success'))
            toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        toastr.success('{{ Session::get('success') }}')

        @endif
            @if(count($errors) > 0)
            toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        toastr.error('{{ implode($errors->all()) }}')
        @endif
        $('#datatables').DataTable({
            sort: false,
            "scrollX": true,
            paging: false,
            searching: false,
            "autoWidth": false,
        })

        $('#bulan').on('change', function () {
            location.replace(`{{url('')}}?month=${$(this).val()}`)
        })
    </script>
</body>
</html>
