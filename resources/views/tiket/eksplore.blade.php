@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="container-fluid">
                <div class="panel-main panel-lighter">
                    {{-- Header --}}
                    <div class="main-header">
                        <div class="page-header">
                            <h3 class="page-name">Eksplore Tiket</h3>
                            <p class="page-maps">
                                <span class="current"> Menampilkan list tiket</span>
                            </p>
                        </div>
                    </div>

                    <div class="main-body" data-select2-id="122">
                        <div class="row justify-content-between" data-select2-id="121">
                            
                            <div class="col-md-8 px-0 pr-1" id="list-ticket">
                                @foreach ($tickets as $ticket)
                                    <a href="{{ route('ticket-detail', $ticket->id) }}">
                                        <div class="card mb-2" id="ticket_template">
                                            <div class="card-body row">

                                                <div class="col-md-8 mt-auto mb-auto d-flex flex-column">
                                                    <h4 class="font-weight-bold mt-1 ticket-name">
                                                        <a href="{{ route('ticket-detail', $ticket->id) }}">
                                                            {{ $ticket->subject }}
                                                        </a>
                                                    </h4>
                                                    <p><span class="text-secondary"><i>#{{ $ticket->no_ticket }}</i></span></p>
                                                    <p class="ticket-description"> 
                                                        • Dibuat oleh {{ $ticket->employee->name }}
                                                        <br>
                                                        • Akan direspon oleh tim <span class="text-danger">{{ $ticket->team }}</span><br>
                                                        • Status diperbarui sejak <span class="text-primary">{{ $ticket->updated_at->diffForHumans() }}</span>
                                                    </p>
                                                </div>

                                                <div class="col-md-4 d-flex flex-column justify-content-around">
                                                    <!-- Dropdown Priority -->
                                                    <div class="col-form-label p-0 ticket-priorities" for="defaultCheck1">
                                                        <i class="fa fa-stop text-info"></i>
                                                        <a href="#" class="dropdown-toggle pl-1"
                                                            style="text-decoration:none;color:black"
                                                            id="priorityMenu_{{ $ticket->id }}" data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">{{ $ticket->priority }}</a>
                                                        <ul class="dropdown-menu" aria-labelledby="priorityMenu_{{ $ticket->id }}">
                                                            <li>
                                                                <a class="dropdown-item dropdown-priority" href="#" data-value="LOW" data-ticket-id="{{ $ticket->id }}">
                                                                    Low
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-priority" href="#" data-value="MEDIUM" data-ticket-id="{{ $ticket->id }}">
                                                                    Medium
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-priority" href="#" data-value="HIGH" data-ticket-id="{{ $ticket->id }}">
                                                                    High
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Dropdown Status -->
                                                    <div class="col-form-label p-0 ticket-statuses" for="defaultCheck1">
                                                        <i class="fa fa-chart-line"></i>
                                                        <a href="#" class="dropdown-toggle pl-1"
                                                            style="text-decoration:none;color:black"
                                                            id="statusMenu_{{ $ticket->id }}" data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">{{ $ticket->status }}</a>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="statusMenu_{{ $ticket->id }}">
                                                            <li>
                                                                <a class="dropdown-item dropdown-status" href="#" data-value="1"
                                                                    data-ticket-id="{{ $ticket->id }}">Open
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-status" href="#" data-value="2"
                                                                    data-ticket-id="{{ $ticket->id }}">Pending
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-status" href="#" data-value="3"
                                                                    data-ticket-id="{{ $ticket->id }}">Resolved
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-status" href="#" data-value="4"
                                                                    data-ticket-id="{{ $ticket->id }}">Waiting</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item dropdown-status" href="#" data-value="5"
                                                                    data-ticket-id="{{ $ticket->id }}">Processed
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <!-- Pagination Links -->
                                {{ $tickets->links() }}
                            </div>

                            <!-- Filter Panel -->
                            <div class="col-md-4">
                                <div class="card" id="filter-section">
                                    <div class="card-body">

                                        <form action="{{ route('explore-ticket-index') }}" method="GET" id="form-filter" autocomplete="off">
                                            <div class="mb-3 d-flex flex-column">

                                                <h6 class="font-weight-bold mb-2">Filter data</h6>
                                                <div class="btn-group w-100">

                                                    <button type="submit" class="btn btn-primary btn-sm btn-filter waves-effect waves-light">
                                                        <i class="fa fa-search"></i> Terapkan
                                                    </button>
                                                    
                                                    <a href="{{ route('explore-ticket-index') }}" class="btn btn-secondary btn-sm btn-reset-filter waves-effect waves-light"> 
                                                        <i class="fa fa-sync"></i> Reset 
                                                    </a>

                                                </div>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="subject" class="col-form-label">Subjek</label>
                                                <input type="text" name="subject" id="subject" class="form-control"
                                                    placeholder="Cari berdasarkan subjek tiket..." autocomplete="off"
                                                    value="{{ request('subject') }}">
                                                </input>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="kode_tiket" class="col-form-label">Kode Tiket</label>
                                                <input type="text" name="no_ticket" id="kode_tiket" class="form-control" autocomplete="off"
                                                    placeholder="# Kode Tiket"
                                                    value="{{ request('no_ticket') }}">
                                                </input>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="team" class="col-form-label">Tim</label>
                                                <select name="team" class="form-control">
                                                    <option value="">-- Pilih Tim --</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->name }}">{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="form-group mt-2">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Open">Open</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Resolved">Resolved</option>
                                                    <option value="Waiting">Waiting</option>
                                                    <option value="Processed">Processed</option>
                                                </select>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                @push('js')
                    <script>
                        $(document).ready(function() {
                            // Handle priority menu
                            $(document).on('click', '.dropdown-item.dropdown-priority', function(e) {
                                e.preventDefault();
                                var selectedText = $(this).text();
                                var ticketId = $(this).data('ticket-id');
                                var priorityValue = $(this).data('value');

                                // Update dropdown text
                                $('#priorityMenu_' + ticketId).text(selectedText);

                                // Send AJAX request to update priority
                                $.ajax({
                                    url: '{{ route('update.ticket') }}',
                                    method: 'POST',
                                    data: {
                                        id: ticketId,
                                        priority: priorityValue,
                                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                                    },
                                    success: function(response) {
                                        alert('Priority updated successfully');
                                        location.reload(); // Reload the page to reflect changes
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText); // Debugging output
                                        alert('Failed to update priority');
                                    }
                                });
                            });

                            // Handle status menu
                            $(document).on('click', '.dropdown-item.dropdown-status', function(e) {
                                e.preventDefault();
                                var selectedText = $(this).text();
                                var ticketId = $(this).data('ticket-id');
                                var statusValue = $(this).data('value');

                                // Update dropdown text
                                $('#statusMenu_' + ticketId).text(selectedText);

                                // Send AJAX request to update status
                                $.ajax({
                                    url: '{{ route('update.ticket') }}',
                                    method: 'POST',
                                    data: {
                                        id: ticketId,
                                        status: statusValue,
                                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                                    },
                                    success: function(response) {
                                        alert('Status updated successfully');
                                        location.reload(); // Reload the page to reflect changes
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText); // Debugging output
                                        alert('Failed to update status');
                                    }
                                });
                            });
                        });
                    </script>
                @endpush
            @endsection
