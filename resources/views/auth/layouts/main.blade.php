<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', "HeTi - Autentikasi")</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    {{-- Icon Logo --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/HeTi_Logo.png') }}">

    <link href="{{asset("assets")}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets")}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets")}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- My Style --}}
    @stack("style")
</head>

<body>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">

            {{-- Content --}}
            @yield("content")

            {{-- Footer --}}
            <div class="mt-5 text-center">
                <div>
                    <p>
                        ©
                        <script>document.write(new Date().getFullYear())</script>
                        All right reserved.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script src="{{asset("assets")}}/libs/jquery/jquery.min.js"></script>
    <script src="{{asset("assets")}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset("assets")}}/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{asset("assets")}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{asset("assets")}}/libs/node-waves/waves.min.js"></script>
    <script src="{{asset("assets")}}/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- My script --}}
    @stack("script")
    <script>
        @if(Session::has("success"))
            toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        toastr.success("{{ Session::get("success") }}")

        @endif
            @if(count($errors) > 0)
            toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        toastr.error("{{ implode($errors->all()) }}")
        @endif
    </script>
</body>
</html>
