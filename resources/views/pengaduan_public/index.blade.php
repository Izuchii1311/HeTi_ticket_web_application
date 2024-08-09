@extends('templates.main')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Pengajuan</h4>
                        <div class="page-title-right">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ url('employee/pengaduan') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->id }}">
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Karyawan</label>
                                    <input type="text" name="employee" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Jenis Pengaduan</label>
                                    <select name="type" class="form-control">
                                        <option value="cuti">Cuti</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="perjadin">Perjalanan Dinas</option>
                                        <option value="lupa-kartu">Tidak Membawa Kartu</option>
                                        <option value="wfh">WFH (Work From Home)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Jenis Pengaduan</label>
                                    <select name="type" class="form-control">
                                        <option value="cuti">Cuti</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="perjadin">Perjalanan Dinas</option>
                                        <option value="lupa-kartu">Tidak Membawa Kartu</option>
                                        <option value="wfh">WFH (Work From Home)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Jenis Pengaduan</label>
                                    <select name="type" class="form-control">
                                        <option value="cuti">Cuti</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="perjadin">Perjalanan Dinas</option>
                                        <option value="lupa-kartu">Tidak Membawa Kartu</option>
                                        <option value="wfh">WFH (Work From Home)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" rows="9" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Tanggal</label>
                                    <input type="text" name="daterange" class="form-control" readonly>
                                    <input type="hidden" name="start_date" value="{{ now()->format('Y-m-d') }}">
                                    <input type="hidden" name="end_date" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">File (optional)</label>
                                    <input type="file" class="form-control" name="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"/>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis Pengajuan</th>
                                        <th width="200px">Tanggal</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(@$data as $value)
                                        <tr>
                                            <td>{{ $value->user->name }}</td>
                                            <td>{{ $value->title }}</td>
                                            <td>{{ $value->start_date }} s.d {{ $value->end_date }}</td>
                                            <td>{{ @$value->reason }}</td>
                                            <td>{{ $value->status }}</td>
                                            <td>
                                                @if($value->status === 'waiting')
                                                    <a href="{{ url('employee/pengaduan/'.$value->id.'/edit') }}" class="btn btn-primary waves-effect waves-light">
                                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
        <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myExtraLargeModalLabel">Extra large modal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Karyawan</label>
                            <input readonly id="employee-name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="formrow-minimum_attendance" class="form-label">Jenis Pengaduan</label>
                            <input readonly id="type-name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="formrow-minimum_attendance" class="form-label">Deskripsi</label>
                            <input readonly id="description-name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="formrow-minimum_attendance" class="form-label">Status</label>
                            <input readonly id="status-name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="formrow-minimum_attendance" class="form-label">Alasan</label>
                            <input readonly id="reason-name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="formrow-minimum_attendance" class="form-label">File (optional)</label>
                            <button type="button" class="btn btn-secondary lihat-file">
                                Lihat File
                            </button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
      $(function() {
    const today = moment().startOf('day');

    // Initialize start_date and end_date input fields
    $('input[name="start_date"]').val(today.format('YYYY-MM-DD'));
    $('input[name="end_date"]').val(today.format('YYYY-MM-DD'));

    // Initialize daterange input field with custom format
    $('input[name="daterange"]').val(today.format('DD-MM-YYYY') + ' s.d ' + today.format('DD-MM-YYYY'));

    // Initialize daterangepicker with custom locale and callback


    // Initialize DataTable
    $('.datatables-basic').DataTable();

    // Handle file view button
    $('.lihat-file').on('click', function () {
        window.open($(this).data('file'), '_blank');
    });

    // Handle view submission button
    $('.btn-lihat').on('click', async function () {
        const { value: no } = await Swal.fire({
            title: '',
            input: 'text',
            inputLabel: 'Nomor Pengajuan',
            inputPlaceholder: 'Masukan Nomor Pengajuan'
        });

        if (no) {
            $.ajax({
                url: `{{url('employee/pengaduan/ajax/code')}}`,
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    'code': no
                },
                error: function () {
                    alert('Terjadi kesalahan');
                },
                success: function (data) {
                    if (data.status) {
                        $('#employee-name').val(data?.data?.employee_name);
                        $('#type-name').val(data?.data?.title);
                        $('#description-name').val(data?.data?.description);
                        $('#status-name').val(data?.data?.status);
                        $('#reason-name').val(data?.data?.reason);
                        $('.bs-example-modal-xl').modal('show');
                        $('.lihat-file').attr('data-file', `{{url('')}}/${data?.data?.file}`);
                        if (data?.data?.file) {
                            $('.lihat-file').show();
                        } else {
                            $('.lihat-file').hide();
                        }
                    } else {
                        Swal.fire("Pengajuan", "Nomor Pengajuan Tidak Ada", "error");
                    }
                }
            });
        }
    });

    @if(Session::has('success'))
        Swal.fire("Berhasil", "{{ Session::get('success') }}.", "success");
    @endif
});
    </script>
@endpush
