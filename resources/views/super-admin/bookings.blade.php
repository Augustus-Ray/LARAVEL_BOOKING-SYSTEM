<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #8e44ad 0%, #663399 100%);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link.active {
            background-color: #8e44ad !important;
        }
        .booking-card {
            border-left: 4px solid #8e44ad;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('super-admin.dashboard') }}">
                <i class="fas fa-crown me-2"></i>Super Admin Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('super-admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.manage-admins') }}">
                            <i class="fas fa-users-cog me-2"></i>Manage Admins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('super-admin.bookings') }}">
                            <i class="fas fa-list me-2"></i>All Bookings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">All Bookings Management</h2>

                <!-- Booking Types Navigation -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#hotels-tab">
                            <i class="fas fa-hotel me-1"></i>Hotels ({{ $bookings['hotels']->total() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ferries-tab">
                            <i class="fas fa-ship me-1"></i>Ferries ({{ $bookings['ferries']->total() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parks-tab">
                            <i class="fas fa-ticket-alt me-1"></i>Parks ({{ $bookings['parks']->total() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities-tab">
                            <i class="fas fa-play me-1"></i>Activities ({{ $bookings['activities']->total() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#beaches-tab">
                            <i class="fas fa-umbrella-beach me-1"></i>Beach Events ({{ $bookings['beaches']->total() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Hotels Tab -->
                    <div class="tab-pane fade show active" id="hotels-tab">
                        <div class="card booking-card">
                            <div class="card-header">
                                <h5><i class="fas fa-hotel me-2"></i>Hotel Bookings</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Hotel</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Guests</th>
                                                <th>Rooms</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Booked Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings['hotels'] as $booking)
                                            <tr>
                                                <td>#{{ $booking->id }}</td>
                                                <td>
                                                    <strong>{{ $booking->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </td>
                                                <td>{{ $booking->hotel->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                                <td>{{ $booking->guests }}</td>
                                                <td>{{ $booking->rooms }}</td>
                                                <td>${{ number_format($booking->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $booking->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $bookings['hotels']->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Ferries Tab -->
                    <div class="tab-pane fade" id="ferries-tab">
                        <div class="card booking-card">
                            <div class="card-header">
                                <h5><i class="fas fa-ship me-2"></i>Ferry Tickets</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Ferry</th>
                                                <th>Route</th>
                                                <th>Departure</th>
                                                <th>Passengers</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Booked Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings['ferries'] as $ticket)
                                            <tr>
                                                <td>#{{ $ticket->id }}</td>
                                                <td>
                                                    <strong>{{ $ticket->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </td>
                                                <td>{{ $ticket->ferry->name }}</td>
                                                <td>{{ $ticket->ferry->departure_port }} → {{ $ticket->ferry->arrival_port }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->departure_date)->format('M d, Y H:i') }}</td>
                                                <td>{{ $ticket->passengers }}</td>
                                                <td>${{ number_format($ticket->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $bookings['ferries']->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Parks Tab -->
                    <div class="tab-pane fade" id="parks-tab">
                        <div class="card booking-card">
                            <div class="card-header">
                                <h5><i class="fas fa-ticket-alt me-2"></i>Theme Park Tickets</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Theme Park</th>
                                                <th>Visit Date</th>
                                                <th>Visitors</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Booked Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings['parks'] as $ticket)
                                            <tr>
                                                <td>#{{ $ticket->id }}</td>
                                                <td>
                                                    <strong>{{ $ticket->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </td>
                                                <td>{{ $ticket->themePark->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->visit_date)->format('M d, Y') }}</td>
                                                <td>{{ $ticket->visitors }}</td>
                                                <td>${{ number_format($ticket->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $bookings['parks']->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Activities Tab -->
                    <div class="tab-pane fade" id="activities-tab">
                        <div class="card booking-card">
                            <div class="card-header">
                                <h5><i class="fas fa-play me-2"></i>Activity Tickets</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Activity</th>
                                                <th>Activity Date</th>
                                                <th>Participants</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Booked Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings['activities'] as $ticket)
                                            <tr>
                                                <td>#{{ $ticket->id }}</td>
                                                <td>
                                                    <strong>{{ $ticket->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </td>
                                                <td>{{ $ticket->parkActivity->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->activity_date)->format('M d, Y H:i') }}</td>
                                                <td>{{ $ticket->participants }}</td>
                                                <td>${{ number_format($ticket->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $bookings['activities']->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Beach Events Tab -->
                    <div class="tab-pane fade" id="beaches-tab">
                        <div class="card booking-card">
                            <div class="card-header">
                                <h5><i class="fas fa-umbrella-beach me-2"></i>Beach Event Tickets</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Beach Event</th>
                                                <th>Event Date</th>
                                                <th>Participants</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Booked Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings['beaches'] as $ticket)
                                            <tr>
                                                <td>#{{ $ticket->id }}</td>
                                                <td>
                                                    <strong>{{ $ticket->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </td>
                                                <td>{{ $ticket->beachEvent->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->event_date)->format('M d, Y H:i') }}</td>
                                                <td>{{ $ticket->participants }}</td>
                                                <td>${{ number_format($ticket->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $bookings['beaches']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
