@extends('templates.main')

@section('content')

    <!-- Display notifications -->
    <div class="notifications-container">
        <h2>Notifications</h2>
        @if ($notifications->isEmpty())
            <p>No notifications</p>
        @else
            <ul>
                @foreach ($notifications as $notification)
                    <li>
                        <!-- Display notification content -->
                        <p>{{ $notification->data['message'] }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
