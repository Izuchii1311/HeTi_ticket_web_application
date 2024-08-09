@extends('templates.main')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        div.dataTables_wrapper {

            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Presensi Karyawan</h4>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Presensi</h4>
                            <div class="mb-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div name="" id="" class="form-group mb-3">
                                            <label for="bulan">Bulan</label>
                                            <select name="" id="bulan" class="form-control">
                                                @foreach (@$months as $month)
                                                    <option {{ $loop->iteration == @$currentMonth ? 'selected' : '' }}
                                                        value="{{ $loop->iteration }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun</label>
                                            <select id="tahun" class="form-control">
                                                <?php
                                                $tahun_sekarang = date('Y');
                                                for ($tahun = 2022; $tahun <= $tahun_sekarang; $tahun++) {
                                                    $selected = $tahun == $currentYear ? 'selected' : '';
                                                    echo "<option value='$tahun' $selected>$tahun</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <table id="datatables" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Nama Heroes</th>
                                            <th colspan="{{ count($dates) }}" style="text-align: center">Tanggal</th>
                                        </tr>
                                        <tr>
                                            @for ($i = 0; $i < count($dates); $i++)
                                                <th>{{ $dates[$i]['day'] }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (@$employees as $employee)
                                            @if (auth()->user()->role === 'admin' || auth()->user()->name === $employee->name)
                                                @if (@$employee->is_active)
                                                    <tr>
                                                        <td>{{ $employee->name }}</td>
                                                        @for ($i = 0; $i < count($dates); $i++)
                                                            <td data-id="{{ $employee->id }}"
                                                                style="vertical-align: middle">
                                                                @php
                                                                    $check = collect($dates[$i]['presensi'])->where(
                                                                        'employee_id',
                                                                        $employee->id,
                                                                    );
                                                                    $jurnal = collect($dates[$i]['jurnal'])->where(
                                                                        'employee_id',
                                                                        $employee->id,
                                                                    );
                                                                @endphp
                                                                @if (count($check) > 0)
                                                                    @if (@$check->first()['type'] == 'presensi')
                                                                        <a href="#"
                                                                            class="btn btn-success waves-effect show-modal waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            data-tanggal="{{ @$dates[$i]['date'] }}"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-html="true"
                                                                            data-bs-title="Jam Masuk : {{ @$check->first()['checkinCustom'] }} <br/> Jam Keluar : {{ @$check->first()['checkoutCustom'] }}"></a>
                                                                    @elseif(@$check->first()['type'] == 'lupa-kartu')
                                                                        <a href="#"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-html="true"
                                                                            data-bs-title="{{ @$check->first()['type'] }} <br /> Jam Masuk : {{ @$check->first()['checkinCustom'] }} <br/> Jam Keluar : {{ @$check->first()['checkoutCustom'] }}"></a>
                                                                    @elseif(@$check->first()['type'] == 'perjadin')
                                                                        <a href="#"
                                                                            class="btn btn-primary waves-effect waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-html="true"
                                                                            data-bs-title="{{ @$check->first()['type'] }} <br /> Jam Masuk : {{ @$check->first()['checkinCustom'] }} <br/> Jam Keluar : {{ @$check->first()['checkoutCustom'] }}"></a>
                                                                    @elseif(@$check->first()['type'] == 'wfh')
                                                                        <a href="#"
                                                                            class="btn waves-effect show-modal waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            data-tanggal="{{ @$dates[$i]['date'] }}"
                                                                            style="background: #000070"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-html="true"
                                                                            data-bs-title="{{ @$check->first()['type'] }} <br /> Jam Masuk : {{ @$check->first()['checkinCustom'] }} <br/> Jam Keluar : {{ @$check->first()['checkoutCustom'] }}"></a>
                                                                    @elseif(@$check->first()['type'] == 'cuti')
                                                                        <a href="#"
                                                                            class="btn waves-effect waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            style="background: #553939"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            data-bs-html="true"
                                                                            data-bs-title="{{ @$check->first()['type'] }} <br /> Jam Masuk : {{ @$check->first()['checkinCustom'] }} <br/> Jam Keluar : {{ @$check->first()['checkoutCustom'] }}"></a>
                                                                    @endif
                                                                @else
                                                                    @if ($dates[$i]['absent'])
                                                                        <a href="#"
                                                                            class="btn btn-danger waves-effect show-modal waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            data-deskripsi="{{ @$dates[$i]['jurnal']['deskripsi'] }}"
                                                                            data-employee="{{ $employee->id }}"
                                                                            data-tanggal="{{ @$dates[$i]['date'] }}"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title="Tidak Hadir"></a>
                                                                    @else
                                                                        <a href="#"
                                                                            class="btn waves-effect show-modal waves-light"
                                                                            data-jurnal="{{ $jurnal }}"
                                                                            style="background: white; border: 1px solid #1818184a"
                                                                            data-deskripsi="{{ @$dates[$i]['jurnal']['deskripsi'] }}"
                                                                            data-employee="{{ $employee->id }}"
                                                                            data-tanggal="{{ @$dates[$i]['date'] }}"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title="Belum Hadir"></a>
                                                                    @endif
                                                                @endif

                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>


            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('admin/presensi') }}" method="post" id="form-modal">
                            {{ csrf_field() }}
                            <input type="hidden" name="employee_id" id="employee-id">
                            <div class="row">
                                <div class="col-12">
                                    <div id="" class="form-group">
                                        <label for="tahun">Tanggal</label>
                                        <input type="text" id="tanggal-presensi" name="tanggal" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div id="" class="form-group">
                                        <label for="tahun">Tipe</label>
                                        <select name="type" id="" class="form-control" readonly>
                                            <option value="presensi">Presensi</option>
                                            <option value="lupa-kartu">Lupa Kartu</option>
                                            <option value="perjadin">Perjalanan Dinas</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="wfh">WFH (Work From Home)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="form-group">
                                        <label for="checkin-presensiinput">Jam Masuk</label>
                                        <input class="form-control" name="checkin" type="time"
                                            value="{{ old('checkin', @$data->checkin ?? '13:45:00') }}"
                                            id="checkin-presensiinput" @if (auth()->user()->role !== 'admin') readonly @endif>
                                    </div>
                                </div>

                                <div class="col-6 mt-3">
                                    <div class="form-group">
                                        <label for="checkout-presensi">Jam Keluar</label>
                                        <input class="form-control" name="checkout" type="time"
                                            value="{{ old('checkout', @$data->checkout ?? '13:45:00') }}"
                                            id="checkout-presensi" @if (auth()->user()->role !== 'admin') readonly @endif>
                                    </div>
                                </div>


                                <div class="col-12 mt-3">
                                    <div id="" class="form-group">
                                        <label for="tahun">Jurnal</label>
                                        <div>
                                            <div class="jurnal-deskripsi p-2 bg-light border rounded ">
                                                </td>

                                                {{-- @if (@$jurnal)
                                    <div class="jurnal-deskripsi p-2 bg-light border rounded"></div>
                                    <input type="text" id="" name="jurnal" class="form-control"  readonly>
                                    @else
                                        <a href="{{ route('jurnal.index') }}" class="btn btn-primary">Tambah Jurnal</a>
                                    @endif --}}
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                    @if (auth()->user()->role === 'admin')
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    @endif


                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="" class="form-group">
                            <label for="tahun">Tanggal</label>
                            <input type="text" name="daterange" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn export-process btn-primary">Export</button>
                    </div>
                </div>
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
                $('input[name="daterange"]').daterangepicker({
                    opens: 'left',
                    startDate: startDate,
                    endDate: endDate,
                }, function(start, end, label) {
                    startDate = start;
                    endDate = end;
                });
            });

            $(".show-modal").on('click', function() {
                const tanggal = $(this).data('tanggal');
                const employeeId = $(this).data('employee');
                $('#tanggal-presensi').val(tanggal);
                $('#employee-id').val(employeeId);

                let listJurnal = $(this).data('jurnal');
                let desk;
                @if (auth()->user()->role == 'admin')
                    if (listJurnal && listJurnal.length > 0){
                    desk = '<a href="{{ route('jurnal.index.') }}" class="btn btn-primary">Lihat Jurnal</a>';
            }else{
                        desk = '<p class="text-danger">Heroes belum mengisi jurnal</p>';
            }
                @elseif (auth()->user()->role == 'employee')
                    if (listJurnal && listJurnal.length > 0){
                        desk = '<a href="{{ route('jurnal.index') }}" class="btn btn-primary">Lihat Jurnal</a>';
                    }else{
                        desk = '<a href="{{ route('jurnal.index') }}" class="btn btn-danger">Tambah Jurnal</a>  Anda belum mengisi jurnal';
                    }
                @endif

                // if (listJurnal && listJurnal.length > 0) {
                //     desk = '<a href="{{ route('jurnal.index') }}" class="btn btn-primary">Lihat Jurnal</a>';
                // } else {
                //     desk = '<a href="{{ route('jurnal.index') }}" class="btn btn-danger">Tambah Jurnal</a>  Anda belum mengisi jurnal';
                // }

                $('.jurnal-deskripsi').html(desk);

                $('#exampleModal').modal('show');
            });



            $('#datatables').DataTable({
                sort: false,
                "scrollX": true,
                paging: false
                // responsive: true
            })

            $('#bulan').on('change', function() {
                location.replace(`{{ url('/admin/presensi') }}?month=${$(this).val()}&year=${$('#tahun').val()}`)
            })
            $('#tahun').on('change', function() {
                location.replace(`{{ url('/admin/presensi') }}?year=${$(this).val()}&month=${$('#bulan').val()}`)
            });


            $(document).on('click', '.export-process', function() {
                location.replace(
                    `{{ url('/admin/presensi/generate') }}?start=${startDate.format('YYYY-MM-DD')}&end=${endDate.format('YYYY-MM-DD')}`
                )
            })

            $(".export ").on('click', function() {
                $('#exportModal').modal('show');

            })

            $(document).on('click', ".remove", function() {
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
                            url: `{{ url('admin/status/') }}/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            error: function() {
                                alert('Terjadi kesalahan');
                            },
                            success: function(data) {
                                location.reload();
                                // console.log(data);
                                Swal.fire("Dihapus", "Data berhasil dihapus.", "success");
                            }
                        });
                    }
                })
            });
        </script>

        <script>
            function updateSelection() {
                var bulan = document.getElementById('bulan').value;
                var tahun = document.getElementById('tahun').value;

                // Logika untuk menangani perubahan nilai dropdown (misalnya, mengirimkan data ke server atau memperbarui tampilan)
                console.log("Bulan yang dipilih:", bulan);
                console.log("Tahun yang dipilih:", tahun);

                // Anda bisa menambahkan logika di sini untuk menyimpan pilihan ke backend atau memperbarui tampilan.
            }
        </script>
    @endpush
