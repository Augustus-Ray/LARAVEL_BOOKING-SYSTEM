@extends('layouts.admin')

@section('title', 'Theme Park Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">🎢 Theme Park Management</h3>
                            <p class="mb-0">{{ Auth::guard('admin')->user()->business_name }}</p>
                        </div>
                        <div class="text-right">
                            <h5 class="mb-0">Today's Revenue</h5>
                            <h4 class="mb-0">${{ number_format($todaysRevenue, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $todaysTickets }}</h3>
                    <p class="text-muted">Today's Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $currentVisitors }}</h3>
                    <p class="text-muted">Current Visitors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-info">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $activeActivities }}</h3>
                    <p class="text-muted">Active Activities</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ number_format($capacityUsage, 1) }}%</h3>
                    <p class="text-muted">Capacity Usage</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="managementTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tickets" type="button" role="tab">
                                <i class="fas fa-ticket-alt"></i> Ticket Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
                                <i class="fas fa-star"></i> Activities
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab">
                                <i class="fas fa-calendar-alt"></i> Events
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="capacity-tab" data-bs-toggle="tab" data-bs-target="#capacity" type="button" role="tab">
                                <i class="fas fa-chart-bar"></i> Capacity Monitor
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="managementTabsContent">
                        <!-- Ticket Management -->
                        <div class="tab-pane fade show active" id="tickets" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Recent Ticket Sales</h5>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#issueTicketModal">
                                    <i class="fas fa-plus"></i> Issue Ticket
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Customer</th>
                                            <th>Ticket Type</th>
                                            <th>Purchase Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTickets as $ticket)
                                        <tr>
                                            <td><code>{{ $ticket->ticket_code }}</code></td>
                                            <td>{{ $ticket->user->name }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ ucfirst($ticket->ticket_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $ticket->created_at->format('M d, H:i') }}</td>
                                            <td>
                                                @if($ticket->status === 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($ticket->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($ticket->status === 'confirmed')
                                                <button class="btn btn-sm btn-outline-success" title="Check In">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No tickets found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Activities Management -->
                        <div class="tab-pane fade" id="activities" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Park Activities</h5>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                    <i class="fas fa-plus"></i> Add Activity
                                </button>
                            </div>

                            <div class="row">
                                @forelse($activities as $activity)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-{{ $activity->is_active ? 'success' : 'secondary' }}">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $activity->name }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="activity{{ $activity->id }}" 
                                                       {{ $activity->is_active ? 'checked' : '' }} 
                                                       onchange="toggleActivity({{ $activity->id }})">
                                                <label class="form-check-label" for="activity{{ $activity->id }}"></label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small">{{ $activity->description }}</p>
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <strong>${{ number_format($activity->price, 2) }}</strong>
                                                    <br><small class="text-muted">Price</small>
                                                </div>
                                                <div class="col-6">
                                                    <strong>{{ $activity->capacity }}</strong>
                                                    <br><small class="text-muted">Max Capacity</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ ($activity->current_bookings / $activity->capacity) * 100 }}%">
                                                        {{ $activity->current_bookings }}/{{ $activity->capacity }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-users"></i> Bookings
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-star fa-3x mb-3"></i>
                                        <p>No activities configured yet</p>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                            Add Your First Activity
                                        </button>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Events Management -->
                        <div class="tab-pane fade" id="events" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Special Events</h5>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                    <i class="fas fa-plus"></i> Schedule Event
                                </button>
                            </div>

                            <div class="row">
                                @forelse($upcomingEvents as $event)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">{{ $event->description }}</p>
                                            <div class="row">
                                                <div class="col-6">
                                                    <strong>{{ $event->event_date->format('M d, Y') }}</strong>
                                                    <br><small class="text-muted">Date</small>
                                                </div>
                                                <div class="col-6">
                                                    <strong>{{ $event->event_time }}</strong>
                                                    <br><small class="text-muted">Time</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge bg-{{ $event->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                        <p>No upcoming events scheduled</p>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Capacity Monitor -->
                        <div class="tab-pane fade" id="capacity" role="tabpanel">
                            <h5>Real-time Capacity Monitor</h5>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Today's Visitor Flow</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="capacityChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Capacity Alerts</h6>
                                        </div>
                                        <div class="card-body">
                                            @if($capacityUsage > 90)
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                Park at 90%+ capacity
                                            </div>
                                            @elseif($capacityUsage > 75)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle"></i> 
                                                High capacity usage
                                            </div>
                                            @else
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle"></i> 
                                                Capacity normal
                                            </div>
                                            @endif

                                            <div class="mt-3">
                                                <h6>Quick Actions</h6>
                                                <button class="btn btn-sm btn-warning mb-2 w-100">
                                                    Temporary Close Entrance
                                                </button>
                                                <button class="btn btn-sm btn-info mb-2 w-100">
                                                    Send Capacity Alert
                                                </button>
                                                <button class="btn btn-sm btn-success w-100">
                                                    Open All Activities
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <h5 class="modal-title">Issue Theme Park Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.park.issue-ticket') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Customer Email</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="ticket_type" class="form-label">Ticket Type</label>
                        <select class="form-select" id="ticket_type" name="ticket_type" required>
                            <option value="">Select Ticket Type</option>
                            <option value="general">General Admission - $25</option>
                            <option value="premium">Premium Pass - $45</option>
                            <option value="vip">VIP Experience - $75</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="visit_date" class="form-label">Visit Date</label>
                        <input type="date" class="form-control" id="visit_date" name="visit_date" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" 
                               min="1" max="10" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Issue Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.park.activities.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="activity_name" class="form-label">Activity Name</label>
                        <input type="text" class="form-control" id="activity_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="activity_description" class="form-label">Description</label>
                        <textarea class="form-control" id="activity_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activity_price" class="form-label">Price ($)</label>
                                <input type="number" class="form-control" id="activity_price" name="price" 
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activity_capacity" class="form-label">Max Capacity</label>
                                <input type="number" class="form-control" id="activity_capacity" name="capacity" 
                                       min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="activity_duration" class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" id="activity_duration" name="duration" 
                               min="5" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="activity_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="activity_active">
                            Active (available for booking)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleActivity(activityId) {
    fetch(`/admin/park/activities/${activityId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Initialize capacity chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('capacityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
            datasets: [{
                label: 'Visitors',
                data: [50, 120, 200, 350, 400, 380, 420, 350, 250],
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 500
                }
            }
        }
    });
});
</script>
@endsection
