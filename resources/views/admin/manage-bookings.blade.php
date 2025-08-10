<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Paradise Island Admin</title>
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
        }
        .booking-card {
            border-left: 4px solid #e74c3c;
        }
        .btn-sm {
            margin: 2px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-user-shield me-2"></i>{{ $admin->business_name }} Admin
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
                        <a class="nav-link active" href="{{ route('admin.manage-bookings') }}">
                            <i class="fas fa-list me-2"></i>Manage Bookings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Bookings - {{ ucfirst(str_replace('_', ' ', $admin->business_type)) }}</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Business Type Navigation -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    @if($admin->canManageBookingType('hotel'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $type === 'hotel' ? 'active' : '' }}" href="{{ route('admin.manage-bookings', ['type' => 'hotel']) }}">
                            <i class="fas fa-hotel me-1"></i>Hotels
                        </a>
                    </li>
                    @endif
                    
                    @if($admin->canManageBookingType('ferry'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $type === 'ferry' ? 'active' : '' }}" href="{{ route('admin.manage-bookings', ['type' => 'ferry']) }}">
                            <i class="fas fa-ship me-1"></i>Ferries
                        </a>
                    </li>
                    @endif
                    
                    @if($admin->canManageBookingType('theme_park'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $type === 'theme_park' ? 'active' : '' }}" href="{{ route('admin.manage-bookings', ['type' => 'theme_park']) }}">
                            <i class="fas fa-ticket-alt me-1"></i>Theme Parks
                        </a>
                    </li>
                    @endif
                    
                    @if($admin->canManageBookingType('beach_event'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $type === 'beach_event' ? 'active' : '' }}" href="{{ route('admin.manage-bookings', ['type' => 'beach_event']) }}">
                            <i class="fas fa-umbrella-beach me-1"></i>Beach Events
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- Bookings Table -->
                <div class="card booking-card">
                    <div class="card-header">
                        <h5><i class="fas fa-list me-2"></i>{{ ucfirst(str_replace('_', ' ', $type)) }} Bookings</h5>
                    </div>
                    <div class="card-body">
                        @if($bookings && count($bookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>
                                            <strong>{{ $booking->user->name }}</strong><br>
                                            <small class="text-muted">{{ $booking->user->email }}</small>
                                        </td>
                                        <td>
                                            @if($type === 'hotel')
                                                {{ $booking->hotel->name }}<br>
                                                <small>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</small>
                                            @elseif($type === 'ferry')
                                                {{ $booking->ferry->name }}<br>
                                                <small>{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</small>
                                            @elseif($type === 'theme_park')
                                                {{ $booking->themePark->name }}<br>
                                                <small>{{ \Carbon\Carbon::parse($booking->visit_date)->format('M d, Y') }}</small>
                                            @elseif($type === 'beach_event')
                                                {{ $booking->beachEvent->name }}<br>
                                                <small>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</small>
                                            @endif
                                        </td>
                                        <td>${{ number_format($booking->total_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ ($booking->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($booking->payment_status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($booking->status !== 'confirmed')
                                            <form method="POST" action="{{ route('admin.booking.update', [$type, $booking->id]) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="btn btn-success btn-sm" title="Confirm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($admin->hasPermission('mark_as_paid') && ($booking->payment_status ?? 'pending') !== 'paid')
                                            <form method="POST" action="{{ route('admin.booking.update', [$type, $booking->id]) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="mark_paid">
                                                <button type="submit" class="btn btn-info btn-sm" title="Mark as Paid">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($admin->hasPermission('cancel_bookings') && $booking->status !== 'cancelled')
                                            <form method="POST" action="{{ route('admin.booking.update', [$type, $booking->id]) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                @csrf
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(method_exists($bookings, 'links'))
                            {{ $bookings->links() }}
                        @endif
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5>No bookings found</h5>
                            <p class="text-muted">No {{ str_replace('_', ' ', $type) }} bookings to display.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Debug: Log form submissions and authentication status
        $(document).ready(function() {
            console.log('Admin manage bookings page loaded');
            console.log('Current admin business type: {{ $admin->business_type ?? "not logged in" }}');
            console.log('Current booking type: {{ $type }}');
            
            $('form').on('submit', function(e) {
                console.log('Form being submitted:', this);
                console.log('Action URL:', $(this).attr('action'));
                console.log('CSRF Token:', $(this).find('input[name="_token"]').val());
                console.log('Action value:', $(this).find('input[name="action"]').val());
                console.log('Status value:', $(this).find('input[name="status"]').val());
            });
        });
    </script>
</body>
</html>
