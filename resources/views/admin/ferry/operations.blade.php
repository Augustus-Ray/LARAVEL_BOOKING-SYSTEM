<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferry Operations - Paradise Island Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link.active {
            background-color: #17a2b8 !important;
            color: white !important;
        }
        .validation-card {
            border-left: 4px solid #17a2b8;
        }
        .valid-booking {
            border-left-color: #28a745;
        }
        .invalid-booking {
            border-left-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-ship me-2"></i>{{ $admin->business_name ?? 'Ferry' }} Operations
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
                        <a class="nav-link active" href="{{ route('admin.ferry.operations') }}">
                            <i class="fas fa-ship me-2"></i>Ferry Operations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ferry.validation') }}">
                            <i class="fas fa-check-circle me-2"></i>Ticket Validation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ferry.schedules') }}">
                            <i class="fas fa-calendar me-2"></i>Schedules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.manage-bookings') }}">
                            <i class="fas fa-list me-2"></i>Manage Bookings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Ferry Operations Dashboard</h2>
                    <div>
                        <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#validationModal">
                            <i class="fas fa-search me-2"></i>Validate Ticket
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issueTicketModal">
                            <i class="fas fa-ticket-alt me-2"></i>Issue Ticket
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Ferry Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle me-2"></i>Ferry Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Ferry Name:</strong> {{ $ferry->name ?? 'All Ferries' }}</p>
                                        <p><strong>Route:</strong> {{ $ferry->route ?? 'Multiple Routes' }}</p>
                                        <p><strong>Capacity:</strong> {{ $ferry->capacity ?? 'N/A' }} passengers</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Today's Departures:</strong> {{ $todayDepartures }}</p>
                                        <p><strong>Available Seats:</strong> {{ $availableSeats }}</p>
                                        <p><strong>Booked Seats:</strong> {{ $bookedSeats }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-clock me-2"></i>Today's Schedule</h5>
                            </div>
                            <div class="card-body">
                                @if($schedules->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Departure Time</th>
                                                <th>Route</th>
                                                <th>Capacity</th>
                                                <th>Booked</th>
                                                <th>Available</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule->departure_time }}</td>
                                                <td>{{ $schedule->route }}</td>
                                                <td>{{ $schedule->capacity }}</td>
                                                <td>{{ $schedule->booked }}</td>
                                                <td>{{ $schedule->capacity - $schedule->booked }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $schedule->status === 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($schedule->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="viewPassengers({{ $schedule->id }})">
                                                            <i class="fas fa-users"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info" onclick="updateSchedule({{ $schedule->id }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
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
                                    <h5>No Schedules Today</h5>
                                    <p class="text-muted">No ferry schedules for today.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Ticket Validations -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-check-circle me-2"></i>Recent Ticket Validations</h5>
                            </div>
                            <div class="card-body">
                                @if($recentValidations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ticket ID</th>
                                                <th>Customer</th>
                                                <th>Hotel Booking</th>
                                                <th>Departure</th>
                                                <th>Validation Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentValidations as $validation)
                                            <tr>
                                                <td>#{{ $validation->ticket_id }}</td>
                                                <td>
                                                    <strong>{{ $validation->customer_name }}</strong><br>
                                                    <small>{{ $validation->customer_email }}</small>
                                                </td>
                                                <td>
                                                    @if($validation->hotel_booking_valid)
                                                        <span class="badge bg-success">Valid Hotel Booking</span><br>
                                                        <small>Hotel: {{ $validation->hotel_name }}</small>
                                                    @else
                                                        <span class="badge bg-danger">No Valid Hotel Booking</span>
                                                    @endif
                                                </td>
                                                <td>{{ $validation->departure_time }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $validation->is_valid ? 'success' : 'danger' }}">
                                                        {{ $validation->is_valid ? 'Valid' : 'Invalid' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($validation->is_valid && !$validation->pass_issued)
                                                    <button class="btn btn-success btn-sm" onclick="issuePass({{ $validation->ticket_id }})">
                                                        <i class="fas fa-id-card me-1"></i>Issue Pass
                                                    </button>
                                                    @elseif($validation->pass_issued)
                                                    <span class="badge bg-info">Pass Issued</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h5>No Recent Validations</h5>
                                    <p class="text-muted">No recent ticket validations to display.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validate Ferry Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="validationForm">
                        <div class="mb-3">
                            <label class="form-label">Ticket ID or Customer Email</label>
                            <input type="text" class="form-control" id="ticketSearch" placeholder="Enter ticket ID or customer email">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="validateTicket()">
                            <i class="fas fa-search me-1"></i>Validate
                        </button>
                    </form>
                    
                    <div id="validationResult" class="mt-4" style="display: none;">
                        <!-- Validation results will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issue Ticket Modal -->
    <div class="modal fade" id="issueTicketModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Ferry Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.ferry.issue-ticket') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Email</label>
                            <input type="email" class="form-control" name="customer_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Departure Schedule</label>
                            <select class="form-control" name="schedule_id" required>
                                <option value="">Select Schedule</option>
                                @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">{{ $schedule->departure_time }} - {{ $schedule->route }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Passengers</label>
                            <input type="number" class="form-control" name="passengers" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Issue Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateTicket() {
            const search = document.getElementById('ticketSearch').value;
            
            // Mock validation result
            const resultDiv = document.getElementById('validationResult');
            resultDiv.innerHTML = `
                <div class="card validation-card valid-booking">
                    <div class="card-body">
                        <h6>Validation Result</h6>
                        <p><strong>Customer:</strong> John Doe (john@example.com)</p>
                        <p><strong>Hotel Booking:</strong> <span class="badge bg-success">Valid</span> - Paradise Resort</p>
                        <p><strong>Check-in:</strong> Aug 10, 2025 | <strong>Check-out:</strong> Aug 15, 2025</p>
                        <p><strong>Ferry Ticket:</strong> <span class="badge bg-success">Valid</span></p>
                        <button class="btn btn-success" onclick="issuePass('${search}')">
                            <i class="fas fa-id-card me-1"></i>Issue Ferry Pass
                        </button>
                    </div>
                </div>
            `;
            resultDiv.style.display = 'block';
        }

        function issuePass(ticketId) {
            if(confirm('Issue ferry pass for this customer?')) {
                alert('Ferry pass issued successfully!');
                bootstrap.Modal.getInstance(document.getElementById('validationModal')).hide();
            }
        }

        function viewPassengers(scheduleId) {
            alert('View passengers functionality - to be implemented');
        }

        function updateSchedule(scheduleId) {
            alert('Update schedule functionality - to be implemented');
        }
    </script>
</body>
</html>
