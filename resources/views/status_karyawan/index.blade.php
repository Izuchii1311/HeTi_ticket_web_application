@extends('templates.main')

@push('style')

@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Status Karyawan</h4>

                        <div class="page-title-right">
                            <a href="{{url('dashboard/status/create')}}" class="btn btn-primary w-sm waves-effect waves-light">Tambah Data</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Data Users</h4>

                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach(@$data as $value)
                                        <tr>
                                            <td>{{$value->status}}</td>
                                            <td>
                                                <a href="{{url('dashboard/status/'.$value->id.'/edit')}}" class="btn btn-primary waves-effect waves-light">
                                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                                </a>
                                                <button class="btn btn-danger remove waves-effect waves-light" data-id="{{$value->id}}" >
                                                    <i class="bx bx-trash font-size-16 align-middle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->


        </div>
    </div>
@endsection

@push('js')

    <script>

        $('.datatables-basic').DataTable({})

        $(document).on('click', ".remove", function () {
            const id = $(this).data('id')
            console.log(id);
            Swal.fire({
                title: "Apa anda yakin?",
                text: "Anda tidak akan dapat memulihkan data ini.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Tidak, batalkan",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{url('dashboard/status/')}}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        error: function () {
                            alert('Terjadi kesalahan');
                        },
                        success: function (data) {
                            location.reload();
                            // console.log(data);
                            Swal.fire("Dihapus", "Data berhasil dihapus.", "success");
                        }
                    });
                }
            })
        });
    </script>
@endpush
