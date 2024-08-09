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
                        <h4 class="mb-sm-0 font-size-18">Tambah Karyawan</h4>

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

                            <form method="post" action="{{url(@$data != null ? 'dashboard/status/'.@$data->id : 'dashboard/status')}}">
                                {{csrf_field()}}
                                @if(@$data)
                                    {{method_field('PATCH')}}
                                @endif
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Status</label>
                                    <input type="text" class="form-control" name="status" id="formrow-firstname-input" placeholder="Status" value="{{old('status', @$data->status)}}">
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
    </script>
@endpush
