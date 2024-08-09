@extends('templates.main')

@push('style')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Karyawan</h4>

                        <div class="page-title-right">
                            <a href="{{url('dashboard/karyawan/create')}}" class="btn btn-primary w-sm waves-effect waves-light">Tambah Data</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Data Karyawan</h4>

                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Nip</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->employee->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->employee->nip }}</td>
                                            <td>
                                                <input type="checkbox" {{ $user->employee->is_active ? 'checked' : '' }} class="status-toogle" data-id="{{ $user->id }}" data-toggle="toggle" data-onstyle="primary">
                                            </td>
                                            <td>
                                                <a href="{{ route('karyawan.edit', $user->id) }}" class="btn btn-primary waves-effect waves-light">
                                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('js')

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Check if the datatable is already initialized
            if (!$.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable({
                    'ordering': false,
                    sort: false,
                    paging: false,
                });
            }

            $('.status-toogle').change(function () {
                const userId = $(this).data('id');
                const isActive = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    url: `{{ url('dashboard/karyawan/ajax/status-active') }}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId,
                        status: isActive
                    },
                    error: function () {
                        alert('Terjadi kesalahan');
                    },
                    success: function (response) {
                        alert('Status berhasil diubah');
                    }
                });
            });
        });
    </script>


@endpush
