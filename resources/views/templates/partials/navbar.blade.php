<div class="navbar-header">
    {{-- Logo --}}
    <div class="d-flex">
        <div class="navbar-brand-box pt-5">
            <a href="{{ route('dashboard') }}" class="logo logo-dark d-flex align-items-center">
                <img src="{{ asset('assets/img/HeTi_Logo_2.png') }}" alt="Logo"
                    height="60" class="mr-2">
                <h3 class="text-white mb-0">HELPDESK TICKETING</h3>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect"
            id="vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>
    </div>

    <div class="d-flex">
        <div class="dropdown d-inline-block">

            {{-- <button type="button" class="btn header-item noti-icon waves-effect"
                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="bx bx-bell bx-tada"></i>
                @if (count(auth()->user()->unreadNotifications) > 0)
                    <span
                        class="badge bg-danger rounded-pill">{{ count(auth()->user()->unreadNotifications) }}</span>
                @endif
            </button> --}}

            {{-- Dropdown Notification --}}
            {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-notifications-dropdown" style="">
                <div class="p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0" key="t-notifications"> Notifications </h6>
                        </div>
                    </div>
                </div>

                <div data-simplebar="init" style="max-height: 230px;" class="">

                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>

                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                    aria-label="scrollable content" style="height: auto; overflow: hidden;">

                                    <div class="simplebar-content" style="padding: 0px;">
                                        @foreach (auth()->user()->notifications()->take(5)->get() as $notif)
                                            <a href="{{ route('notification.read', $notif->id) }}"
                                                class="text-reset notification-item ">
                                                <div class="d-flex {{ $notif->read_at ? 'bg-light' : '' }}">
                                                    <div class="avatar-xs me-3">
                                                        <span
                                                            class="avatar-title bg-primary rounded-circle font-size-16">
                                                            <i class="{{ $notif->data['icon'] }}"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1" key="t-your-order">
                                                            {{ $notif->data['title'] }}</h6>
                                                        <div class="font-size-12 text-muted">
                                                            <p class="mb-1" key="t-grammer">
                                                                {{ $notif->data['message'] }}
                                                            </p>
                                                            <p class="mb-0">
                                                                <i class="mdi mdi-clock-outline"></i>
                                                                <span
                                                                    key="t-min-ago">{{ $notif->created_at->timezone(7)->format('d M Y H:i:s') }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>

                    </div>

                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                    </div>

                    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                        <div class="simplebar-scrollbar"
                            style="height: 0px; display: none; transform: translate3d(0px, 0px, 0px);">
                        </div>
                    </div>

                </div>

                <div class="p-2 border-top d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center"
                        href="{{ route('notification.index') }}">
                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">Lihat
                            Semua Notifikasi</span>
                    </a>
                </div>

            </div> --}}
        </div>

        <div class="dropdown d-none d-lg-inline-block ms-1">
            <button type="button" class="btn header-item noti-icon waves-effect"
                data-bs-toggle="fullscreen">
                <i class="bx bx-fullscreen"></i>
            </button>

        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user"
                    src="{{ asset(Auth::user()->profile_picture ? 'storage/images/users/'.Auth::user()->profile_picture : 'assets/img/default_image.jpg') }}"
                    alt="{{ Auth::user()->name }}">


                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->employee->name }}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>

            {{-- Dropdown menu --}}
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item text-primary" href="{{ route('profil.index') }}">
                    <i class="bx bx-pencil font-size-16 align-middle me-1 text-primary"></i>
                    <span>Edit Profil </span>
                </a>
                <a class="dropdown-item text-danger" href="{{ url('logout') }}">
                    <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

    </div>

</div>
