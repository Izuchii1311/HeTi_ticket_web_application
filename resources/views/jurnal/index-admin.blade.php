@extends('templates.main')

@section('content')
<div class="container mt-5 pt-5 ">
    <h1>Daftar Jurnal</h1>

    @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jurnal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        <a href="{{ route('jurnal.show', $value->id) }}" class="btn btn-primary waves-effect waves-light">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>Lihat jurnal
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
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();

        @if (Session::has('success'))
            Swal.fire("Berhasil", "{{ Session::get('success') }}", "success");
        @endif
    });
</script>
@endpush
