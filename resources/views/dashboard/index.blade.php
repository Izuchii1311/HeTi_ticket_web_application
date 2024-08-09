@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Welcome Message --}}
            <div class="row">
                <div class="col-12 my-4">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        @if (Auth::check())
                            @if (Auth::user()->role == 'admin')
                                <h1>Dashboard Admin</h1>
                            @elseif (Auth::user()->role == 'agent')
                                <h1>Dashboard Agen</h1>
                            @else
                                <h1>Main Page</h1>
                            @endif
                        @else
                            <p>You are not logged in.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    {{-- Pickup Ticket Card --}}
                    <div class="card">
                        <div class="card-body text-center">
                            <img class="rounded-circle"
                                 src="{{ asset(Auth::user()->profile_picture ? 'storage/images/users/'.Auth::user()->profile_picture : 'assets/img/default_image.jpg') }}"
                                 alt="{{ Auth::user()->name }}" width="100" height="100">
                            <h4 class="mt-2">
                                @auth
                                    {{ Auth::user()->name }}
                                @endauth
                            </h4>
                        </div>
                    </div>

                    @if ($user->role != 'customer-service')
                        <div class="card mt-3">
                            <div class="card-body text-center">
                                <h5>Ticket Left</h5>
                                <h3>{{ $openTicket }}</h3>
                                @if ($latestTicket)
                                    <a href="{{ route('pickup-ticket', $latestTicket->id) }}" class="btn btn-danger">Pickup Ticket</a>
                                @else
                                    <span>No tickets available</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-9">
                    {{-- Status Card --}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Open</h5>
                                    <h3>{{ $openTicket }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Pending</h5>
                                    <h3>{{ $pendingTicket }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Waiting</h5>
                                    <h3>{{ $waitingTicket }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Processed</h5>
                                    <h3>{{ $processedTicket }}</h3>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Resolved</h5>
                                    <h3>{{ $resolvedTicket }}</h3>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    {{-- Card Data --}}
                    <div class="card">
                        <div class="card-body">
                            <!-- Form Pencarian -->
                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <h5>Recent Tickets</h5>
                                    <p><i>Tiket yang sudah dipickup / status tiket tidak open.</i></p>
                                </div>
                                <div class="col-md-8">
                                    <form method="GET" action="{{ route('dashboard') }}" class="form-inline mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan kode tiket" autocomplete="off" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-success" type="submit"><i class='bx bx-search-alt'></i> Cari</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if (Session::has('success_pickup'))
                                <div class="alert alert-success">
                                    {{ Session::get('success_pickup') }}
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Tiket</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Team</th>
                                        <th>Tanggal dipickup</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentTickets as $ticket)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ticket->no_ticket }}</td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td>{{ $ticket->status }}</td>
                                            <td>{{ $ticket->team }}</td>
                                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('ticket-detail', $ticket->id) }}" class="btn btn-info">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No tickets found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            {{ $recentTickets->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->role == 'admin')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-head my-4 mx-4">
                                <h4>Bagan tiket {{ $employee->name }}</h4>
                                <p><i>Data bagan yang diresolve oleh {{ $employee->name }}</i></p>
                                <hr>
                            </div>
                            <canvas id="statusChartTeam" width="400" height="200" class="my-4 mx-4"></canvas>
                        </div>
                    </div>

                    <!-- Card untuk Data Tiket -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4>Data tiket keseluruhan</h4>
                                <p><i>Data tiket hasil perhitungan keseluruhan</i></p>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>Status Open</h5>
                                            <h3>{{ $countAllOpenTicket }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>Status Waiting</h5>
                                            <h3>{{ $countAllWaitingTicket }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>Status Processed</h5>
                                            <h3>{{ $countAllProcessedTicket }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>Status Pending</h5>
                                            <h3>{{ $countAllPendingTicket }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>Status Resolved</h5>
                                            <h3>{{ $countAllResolvedTicket }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card untuk Bagan Keseluruhan -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-head my-4 mx-4">
                                <h4>Bagan tiket keseluruhan</h4>
                                <p><i>Data bagan secara keseluruhan</i></p>
                            </div>
                            <canvas id="statusChartOverall" width="400" height="200" class="my-4 mx-4"></canvas>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
@endsection

@push('js')
    <script>
        // Existing script for team chart
        const dataTeam = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Open',
                    data: @json($openTickets),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Pending',
                    data: @json($pendingTickets),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Waiting',
                    data: @json($waitingTickets),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Processed',
                    data: @json($processedTickets),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Resolved',
                    data: @json($resolvedTickets),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                }
            ]
        };

        const configTeam = {
            type: 'line',
            data: dataTeam,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        };

        const ctxTeam = document.getElementById('statusChartTeam').getContext('2d');
        new Chart(ctxTeam, configTeam);

        const dataOverall = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Open',
                    data: @json($overallOpenTickets),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Pending',
                    data: @json($overallPendingTickets),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Waiting',
                    data: @json($overallWaitingTickets),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Processed',
                    data: @json($overallProcessedTickets),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                },
                {
                    label: 'Resolved',
                    data: @json($overallResolvedTickets),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Added for smooth line
                }
            ]
        };

        const configOverall = {
            type: 'line',
            data: dataOverall,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        };

        const ctxOverall = document.getElementById('statusChartOverall').getContext('2d');
        new Chart(ctxOverall, configOverall);

    </script>
@endpush
