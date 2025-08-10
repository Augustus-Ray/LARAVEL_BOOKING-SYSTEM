<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Paradise Island Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link.active {
            background-color: #e74c3c !important;
            color: white !important;
        }
        .room-card {
            border-left: 4px solid #28a745;
            transition: transform 0.2s;
        }
        .room-card.unavailable {
            border-left-color: #dc3545;
        }
        .room-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-hotel me-2"></i>{{ $admin->business_name ?? 'Hotel' }} Management
            </a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.hotel.management') }}">
                            <i class="fas fa-hotel me-2"></i>Hotel Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.manage-bookings') }}">
                            <i class="fas fa-list me-2"></i>Manage Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.hotel.reports') }}">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.hotel.promotions') }}">
                            <i class="fas fa-tags me-2"></i>Promotions
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Hotel Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus me-2"></i>Add Room
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Hotel Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle me-2"></i>Hotel Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $hotel->name }}</p>
                                        <p><strong>Location:</strong> {{ $hotel->location }}</p>
                                        <p><strong>Rating:</strong> 
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $hotel->rating ? '' : '-o' }} text-warning"></i>
                                            @endfor
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Total Rooms:</strong> {{ $rooms->count() }}</p>
                                        <p><strong>Available Rooms:</strong> {{ $rooms->where('is_available', true)->count() }}</p>
                                        <p><strong>Occupied Rooms:</strong> {{ $rooms->where('is_available', false)->count() }}</p>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editHotelModal">
                                    <i class="fas fa-edit me-1"></i>Edit Hotel Info
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Management -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-bed me-2"></i>Room Availability</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @forelse($rooms as $room)
                                    <div class="col-md-4 mb-3">
                                        <div class="card room-card {{ !$room->is_available ? 'unavailable' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1">Room {{ $room->room_number }}</h6>
                                                    <span class="badge bg-{{ $room->is_available ? 'success' : 'danger' }}">
                                                        {{ $room->is_available ? 'Available' : 'Occupied' }}
                                                    </span>
                                                </div>
                                                <p class="text-muted mb-1">{{ $room->room_type }}</p>
                                                <p class="mb-2">${{ number_format($room->price_per_night, 2) }}/night</p>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button class="btn btn-outline-primary" onclick="editRoom({{ $room->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-{{ $room->is_available ? 'warning' : 'success' }}" 
                                                            onclick="toggleAvailability({{ $room->id }})">
                                                        <i class="fas fa-{{ $room->is_available ? 'lock' : 'unlock' }}"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 text-center py-4">
                                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                        <h5>No Rooms Available</h5>
                                        <p class="text-muted">Add rooms to start managing your hotel.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-calendar-check me-2"></i>Recent Bookings</h5>
                            </div>
                            <div class="card-body">
                                @if($recentBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Room</th>
                                                <th>Check-in</th>
                                                <th>Check-out</th>
                                                <th>Status</th>
                                                <th>Payment</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentBookings as $booking)
                                            <tr>
                                                <td>
                                                    <strong>{{ $booking->user->name }}</strong><br>
                                                    <small>{{ $booking->user->email }}</small>
                                                </td>
                                                <td>Room {{ $booking->room_number ?? 'TBD' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ ($booking->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($booking->payment_status ?? 'pending') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="viewBooking({{ $booking->id }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if($booking->status !== 'confirmed')
                                                        <button class="btn btn-outline-success" onclick="confirmBooking({{ $booking->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5>No Recent Bookings</h5>
                                    <p class="text-muted">No recent bookings to display.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.hotel.rooms.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" name="room_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room Type</label>
                            <select class="form-control" name="room_type" required>
                                <option value="">Select Type</option>
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                                <option value="Presidential">Presidential</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per Night</label>
                            <input type="number" class="form-control" name="price_per_night" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Occupancy</label>
                            <input type="number" class="form-control" name="max_occupancy" min="1" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1" checked>
                            <label class="form-check-label">Available for booking</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editRoom(roomId) {
            // Implementation for editing room
            alert('Edit room functionality - to be implemented');
        }

        function toggleAvailability(roomId) {
            if(confirm('Are you sure you want to change room availability?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/hotel/rooms/${roomId}/toggle`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function viewBooking(bookingId) {
            // Implementation for viewing booking details
            alert('View booking functionality - to be implemented');
        }

        function confirmBooking(bookingId) {
            if(confirm('Confirm this booking?')) {
                // Implementation for confirming booking
                alert('Confirm booking functionality - to be implemented');
            }
        }
    </script>
</body>
</html>
