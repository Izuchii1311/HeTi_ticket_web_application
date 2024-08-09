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
                        <h4 class="mb-sm-0 font-size-18">Pengajuan</h4>

                        <div class="page-title-right">

                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="{{url('admin/pengaduan/'.@$data->id)}}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Karyawan</label>
                                    <input type="text"  class="form-control" disabled value="{{@$data->user->name}}">

                                </div>


                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Jenis Pengajuan</label>
                                    <input type="text" class="form-control" disabled value="{{@$data->title}}">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Deskripsi</label>
                                    <textarea type="text" class="form-control" rows="9" name="description" disabled>{{@$data->description}}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Alasan</label>
                                    <textarea type="text" class="form-control" rows="3" name="reason">{{@$data->reason}}</textarea>
                                </div>
                                @if(@$data->file)
                                <div class="mb-3">
                                    <p class="form-label">File</p>
                                        <button type="button" class="btn btn-secondary lihat-file">
                                            Lihat File
                                        </button>
                                </div>
                                @endif
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="waiting" disabled {{@$data->status == "waiting" ? "selected" : ""}}>Menunggu</option>
                                        <option value="reject"{{@$data->status == "reject" ? "selected" : ""}}>Tolak</option>
                                        <option value="approved"{{@$data->status == "approved" ? "selected" : ""}}>Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" disabled value="{{@$data->start_date}} - {{@$data->end_date}}">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->


        </div>
    </div>
@endsection

@push('js')

    <script>
        $('.datatables-basic').DataTable({

        })

        @if(Session::has('success'))
            Swal.fire("Berhasil", "{{ Session::get('success') }}.", "success");
        @endif

        @if(@$data->file)
        $('.lihat-file').on('click', function () {
            window.open('{{url(@$data->file)}}', '_blank');
        })
        @endif
    </script>
@endpush
