@extends('templates.main')

@push('style')
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="panel-main panel-lighter">

                <div class="main-header">
                    <div class="page-header">
                        <h3 class="page-name">Daftar Tiket</h3>
                        <p class="page-name">Kumpulan data tiket yang sudah dibuat</p>
                    </div>
                </div>

                <!-- body -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                 {{-- Display success message --}}
                                 @if (Session::has('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Tiket</th>
                                            <th>Subject</th>
                                            {{-- <th>Name</th> --}}
                                            <th>Type</th>
                                            <th>Team</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tickets as $ticket)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ticket->no_ticket }}</td>
                                                <td>{{ $ticket->subject }}</td>
                                                {{-- <td>{{ $ticket->name }}</td> --}}
                                                <td>{{ $ticket->type }}</td>
                                                <td>{{ $ticket->team }}</td>
                                                <td>
                                                    @if ($ticket->priority == "HIGH")
                                                        <h5><span class="badge bg-danger">{{ $ticket->priority }}</span></h5>
                                                    @elseif ($ticket->priority == "MEDIUM")
                                                        <h5><span class="badge bg-warning">{{ $ticket->priority }}</span></h5>
                                                    @else
                                                        <h5><span class="badge bg-info">{{ $ticket->priority }}</span></h5>
                                                    @endif
                                                </td>
                                                <td>{{ $ticket->status }}</td>
                                                {{-- <td>{{ $ticket->created_at->diffForHumans() }}</td> --}}
                                                <td>{{ $ticket->created_at_indo }}</td>
                                                <td>
                                                    <a href="{{ route('ticket-detail', $ticket->id) }}" class="btn btn-info">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Belum ada tiket yang dibuat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
