@extends('templates.main')

@section('content')
<div class="container mt-5 pt-5">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title mb-5">Activity</h4>
            <ul class="verti-timeline list-unstyled">
                @foreach ($data as $jurnal)
                    <li class="event-list">
                        <div class="event-timeline-dot mt-4">
                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0 mt-4 me-3">
                                <h5 class="font-size-14">{{ $jurnal->created_at->format('d M Y H:i:s') }}
                                    <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                </h5>
                            </div>
                            <div class="flex-grow-1 bg-light rounded p-4 shadow">
                                <div class="short-description bg-light rounded-border shadow">
                                    <strong>Nama Heroes: {{ $jurnal->employee->name }}</strong><br>
                                    <strong>Judul Jurnal: {!! $jurnal->judul !!}</strong><br>
                                    <strong>Deskripsi:</strong><br>
                                    <div class="border border-dark rounded-border p-4 bg-white mt-2" id="fullDescription{{ $jurnal->id }}">
                                        <p>{!! $jurnal->deskripsi !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
</div>

<style>
    .rounded-border {
        border-radius: 15px; /* Memberikan efek melengkung pada border */
    }
</style>
@endsection
