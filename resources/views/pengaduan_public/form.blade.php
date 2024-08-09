@extends('templates.main')


@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

                            <form method="post" action="{{url('employee/pengaduan/'.@$data->id)}}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Karyawan</label>
                                    <select name="employee" class="form-control" disabled>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->name }}" {{ $loggedInUserName == $employee->name ? "selected" : "" }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Jenis Pengajuan</label>
                                    <select name="type" class="form-control" {{ @$data->status == "waiting" ?  : " " }}>
                                        <option value="title" >{{ @$data->title }}</option>
                                        <option value="cuti" {{ @$data->type == "cuti" ? "selected" : "" }}>Cuti</option>
                                        <option value="sakit" {{ @$data->type == "sakit" ? "selected" : "" }}>Sakit</option>
                                        <option value="perjadin" {{ @$data->type == "perjadin" ? "selected" : "" }}>Perjalanan Dinas</option>
                                        <option value="lupa-kartu" {{ @$data->type == "lupa-kartu" ? "selected" : "" }}>Tidak Membawa Kartu</option>
                                        <option value="wfh" {{ @$data->type == "wfh" ? "selected" : "" }}>WFH (Work From Home)</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Deskripsi</label>
                                    <textarea type="text" class="form-control" rows="9" name="description" >{{@$data->description}}</textarea>
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
                                    <select name="status" class="form-control" readonly>
                                        <option value="waiting"  {{@$data->status == "waiting" ? "selected" : ""}}>Menunggu</option>

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-minimum_attendance" class="form-label">Tanggal</label>
                                    <input type="text" name="daterange" class="form-control" value="{{@$data->start_date}} - {{@$data->end_date}}">
                                    <input type="hidden" name="start_date" value="{{@$data->start_date}}">
                                    <input type="hidden" name="end_date" value="{{@$data->end_date}}">
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>

        let startDate = moment().startOf('month');
        let endDate = moment().endOf('month');

        $(function() {
    // Dapatkan startDate dan endDate dari input hidden jika ada
    let startDate = moment($('input[name="start_date"]').val(), 'YYYY-MM-DD') || moment().startOf('month');
    let endDate = moment($('input[name="end_date"]').val(), 'YYYY-MM-DD') || moment().endOf('month');

    // Inisialisasi daterangepicker dengan tanggal yang didapat
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        startDate: startDate,
        endDate: endDate,
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            weekLabel: 'W',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    }, function(start, end, label) {
        // Update input hidden dengan tanggal yang dipilih
        $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    });

    // Inisialisasi DataTable
    $('.datatables-basic').DataTable();

    @if(@$data->file)
    $('.lihat-file').on('click', function () {
        window.open('{{url(@$data->file)}}', '_blank');
    })
    @endif

    $('.btn-lihat').on('click', async function () {
        const { value: no } = await Swal.fire({
            title: '',
            input: 'text',
            inputLabel: 'Nomor Pengajuan',
            inputPlaceholder: 'Masukan Nomor Pengajuan'
        })

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
                        $('#employee-name').val(data?.data?.employee_name)
                        $('#type-name').val(data?.data?.title)
                        $('#description-name').val(data?.data?.description)
                        $('#status-name').val(data?.data?.status)
                        $('#reason-name').val(data?.data?.reason)
                        $('.bs-example-modal-xl').modal('show');
                        $('.lihat-file').attr('data-file', `{{url('')}}/${data?.data?.file}`);
                        if (data?.data?.file) {
                            $('.lihat-file').show()
                        } else {
                            $('.lihat-file').hide()
                        }
                    } else {
                        Swal.fire("Pengajuan", "Nomor Pengajuan Tidak Ada", "error");
                    }
                }
            });
        }
    })

    @if(Session::has('success'))
        Swal.fire("Berhasil", "{{ Session::get('success') }}.", "success");
    @endif
});
    </script>
@endpush
