<div id="sidebar-menu" class="pt-5">
    <ul class="metismenu list-unstyled" id="side-menu">

        <li class="menu-title" key="t-menu">Menu</li>

        {{-- Sidebar Admin --}}
        @if (Auth::user()->role === 'admin')
            <li>
                <a href="{{ route('dashboard') }}" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-dashboard">Dashboard</span>
                </a>
            </li>
            <li class="menu-item" role="menuitem">
                <a class="menu-link collapse" data-toggle="collapse" role="button"
                    aria-expanded="true">
                    <i class='bx bxs-user-detail'></i> Manajemen Karyawan
                </a>
                <div class="collapse-menu collapse show" id="mnu_ticket">
                    <div class="collapse-content py-0">
                        <ul class="sidebar-menu">
                            {{-- <li>
                                <a href="{{ url('dashboard/status') }}" class="waves-effect">
                                    <i class="bx bx-user-check"></i>
                                    <span key="t-dashboard">Status Karyawan</span>
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{ url('dashboard/karyawan') }}" class="waves-effect">
                                    <i class="bx bx-user"></i>
                                    <span key="t-dashboard">Karyawan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <li class="menu-item" role="menuitem">
                <a class="menu-link collapse" data-toggle="collapse" role="button"
                    aria-expanded="true">
                    <i class="fas fa-ticket-alt menu-icon"></i> Manajemen Tiket
                </a>
                <div class="collapse-menu collapse show" id="mnu_ticket">
                    <div class="collapse-content py-0">
                        <ul class="sidebar-menu">
                            <li class="menu-item" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_explore" href="{{ route('tickets') }}">
                                    <i class='bx bx-table'></i> Daftar Tiket
                                </a>
                            </li>
                            <li class="menu-item active" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_new" href="{{ route('ticket-create') }}">
                                    <i class='bx bx-purchase-tag-alt'></i> Tiket Baru
                                </a>
                            </li>
                            <li class="menu-item" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_explore" href="{{ route('explore-ticket-index') }}">
                                    <i class="fas fa-tags menu-icon"></i> Eksplore Tiket
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <li class="menu-item" role="menuitem">
                <a class="menu-link collapse" data-toggle="collapse" role="button"
                    aria-expanded="true">
                    <i class='bx bx-cog' ></i> Konfigurasi sistem
                </a>
                <div class="collapse-menu collapse show" id="mnu_ticket">
                    <div class="collapse-content py-0">
                        <ul class="sidebar-menu">
                            <li class="menu-item" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_explore" href="{{ route('template-comments') }}">
                                    <i class='bx bxs-message-dots'></i> Template balasan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

        {{-- Sidebar Agent --}}
        @elseif (Auth::user()->role === 'agent')
            <li>
                <a href="{{ route('dashboard') }}" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-dashboard">Dashboard</span>
                </a>
            </li>

            <li class="menu-item" role="menuitem">
                <a class="menu-link collapse" data-toggle="collapse" role="button"
                    aria-expanded="true">
                    <i class="fas fa-ticket-alt menu-icon"></i> Manajemen Tiket
                </a>
                <div class="collapse-menu collapse show" id="mnu_ticket">
                    <div class="collapse-content py-0">
                        <ul class="sidebar-menu">
                            <li class="menu-item" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_explore" href="{{ route('explore-ticket-index') }}">
                                    <i class="fas fa-tags menu-icon"></i> Eksplore Tiket
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

        {{-- Sidebar Customer Service --}}
        @else
            <li>
                <a href="{{ route('dashboard') }}" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-dashboard">Dashboard</span>
                </a>
            </li>
            <li class="menu-item" role="menuitem">
                <a class="menu-link collapse" data-toggle="collapse" role="button"
                    aria-expanded="true">
                    <i class="fas fa-ticket-alt menu-icon"></i> Manajemen Tiket
                </a>
                <div class="collapse-menu collapse show" id="mnu_ticket">
                    <div class="collapse-content py-0">
                        <ul class="sidebar-menu">
                            <li class="menu-item" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_explore" href="{{ route('tickets') }}">
                                    <i class='bx bx-table'></i> Daftar Tiket
                                </a>
                            </li>
                            <li class="menu-item active" role="menuitem">
                                <a class="menu-link" data-self-code="mnu_ticket_new" href="{{ route('ticket-create') }}">
                                    <i class='bx bx-purchase-tag-alt'></i> Tiket Baru
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        @endif

    </ul>
</div>
