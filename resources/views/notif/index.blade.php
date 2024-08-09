@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items justify-content-between">
                        <h4 class="mb-0 font-size-18">Semua Notifikasi</h4>
                        <div class="page-title-right">

                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Notifikasi</li>
                            </ol>
                            <div>
                                @if (auth()->user()->notifications()->count() == 0)
                                <div>
                                    <button class="btn btn-sm btn-outline-primary ml-1 mt-1 mt-md-0" disabled>
                                        <i data-feather="eye"></i> <span>Baca Semua Notifikasi</span>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger ml-1 mt-1 mt-md-0" disabled>
                                        <i data-feather="trash"></i> <span>Bersihkan Notifikasi Terbaca</span>
                                    </button>
                                </div>
                            @else
                                <div>
                                    @if (auth()->user()->unreadNotifications()->count() == 0)
                                        <button class="btn btn-sm btn-outline-primary ml-1 mt-1 mt-md-0" disabled>
                                            <i data-feather="eye"></i> <span>Baca Semua Notifikasi</span>
                                        </button>
                                    @else
                                        <a id="btn-read-all" href="{{ route('notification.readall') }}" class="btn btn-sm btn-outline-primary ml-1 mt-1 mt-md-0">
                                            <i data-feather="eye"></i> <span>Baca Semua Notifikasi</span>
                                        </a>
                                    @endif

                                    @if (auth()->user()->readNotifications()->count() == 0)
                                        <button class="btn btn-sm btn-outline-danger ml-1 mt-1 mt-md-0" disabled>
                                            <i data-feather="trash"></i> <span>Bersihkan Notifikasi Terbaca</span>
                                        </button>
                                    @else
                                        <a id="btn-clear-read" href="{{ route('notification.clearread') }}" class="btn btn-sm btn-outline-danger ml-1 mt-1 mt-md-0">
                                            <i data-feather="trash"></i> <span>Bersihkan Notifikasi Terbaca</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Notifikasi</h4>
                            <div data-simplebar style="">
                                @foreach (auth()->user()->notifications as $notif)
                                    <a href="{{ route('notification.read', $notif->id) }}" class="text-reset notification-item">
                                        <div class="d-flex {{ $notif->read_at? 'bg-light': '' }}">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="{{ $notif->data['icon'] }}"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $notif->data['title'] }}</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">{{ $notif->data['message'] }}</p>
                                                    <p class="mb-0">
                                                        <i class="mdi mdi-clock-outline"></i>
                                                        <span>{{ $notif->created_at->timezone(7)->format('d M Y H:i:s') }}</span>
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
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
@endsection

@push('js')
    <script>
        let isRtl = $('html').attr('data-textdirection') === 'rtl';

        $('#btn-read-all').on('click', function(e){
            e.preventDefault();

            Swal.fire({
                title: 'Baca Semua Notifikasi',
                text: 'Apakah anda yakin ingin menandai semua notifikasi telah dibaca?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'order-2 btn btn-primary',
                    cancelButton: 'order-1 btn btn-outline-danger me-2'
                },
                buttonsStyling: false
            })
            .then((result) => {
                if (result.value) {
                    let url = $(this).attr('href');

                    $.ajax({
                        url: url,
                        method: 'GET',
                    }).then(function(res){
                        console.log(res);
                        if (res.success) {
                            location.reload();
                            toastr['success'](res.success, 'Success!', {
                                closeButton: true,
                                tapToDismiss: false,
                                rtl: isRtl
                            });
                        } else {
                            toastr['error'](res.success, 'Error!', {
                                closeButton: true,
                                tapToDismiss: false,
                                rtl: isRtl
                            });
                        }
                    });
                }
            });
        })

        $('#btn-clear-read').on('click', function(e){
            e.preventDefault();

            Swal.fire({
                title: 'Hapus Semua Notifikasi Terbaca',
                text: 'Apakah anda yakin ingin menghapus semua notifikasi yang telah dibaca?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'order-2 btn btn-primary',
                    cancelButton: 'order-1 btn btn-outline-danger me-2'
                },
                buttonsStyling: false
            })
            .then((result) => {
                if (result.value) {
                    let url = $(this).attr('href');

                    $.ajax({
                        url: url,
                        method: 'GET',
                    }).then(function(res){
                        if (res.success) {
                            location.reload();
                            toastr['success'](res.success, 'Success!', {
                                closeButton: true,
                                tapToDismiss: false,
                                rtl: isRtl
                            });
                        } else {
                            toastr['error'](res.success, 'Error!', {
                                closeButton: true,
                                tapToDismiss: false,
                                rtl: isRtl
                            });
                        }
                    });
                }
            });
        })
    </script>
@endpush
