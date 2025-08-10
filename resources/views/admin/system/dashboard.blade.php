@extends('layouts.admin')

@section('title', 'System Administration')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">🛠️ System Administration</h3>
                            <p class="mb-0">Complete Platform Management</p>
                        </div>
                        <div class="text-right">
                            <h5 class="mb-0">System Status</h5>
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-circle"></i> Online
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $totalUsers }}</h3>
                    <p class="text-muted">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $totalBusinesses }}</h3>
                    <p class="text-muted">Active Businesses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                    <h3 class="mt-2">{{ $totalBookings }}</h3>
                    <p class="text-muted">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-info">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                    <h3 class="mt-2">${{ number_format($totalRevenue, 0) }}</h3>
                    <p class="text-muted">Platform Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="systemTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                                <i class="fas fa-users"></i> User Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="businesses-tab" data-bs-toggle="tab" data-bs-target="#businesses" type="button" role="tab">
                                <i class="fas fa-building"></i> Business Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab">
                                <i class="fas fa-edit"></i> Content Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                                <i class="fas fa-chart-line"></i> System Reports
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="systemTabsContent">
                        <!-- User Management -->
                        <div class="tab-pane fade show active" id="users" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>User Management</h5>
                                <div>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="fas fa-plus"></i> Add User
                                    </button>
                                    <button class="btn btn-info" onclick="exportUsers()">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Search users..." id="userSearch">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="userFilter">
                                        <option value="">All Users</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="verified">Email Verified</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="filterUsers()">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Joined</th>
                                            <th>Status</th>
                                            <th>Bookings</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning">Unverified</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->total_bookings ?? 0 }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewUser({{ $user->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" 
                                                        onclick="editUser({{ $user->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="suspendUser({{ $user->id }})" title="Suspend">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No users found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $users->links() }}
                            </div>
                        </div>

                        <!-- Business Management -->
                        <div class="tab-pane fade" id="businesses" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Business Management</h5>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBusinessModal">
                                    <i class="fas fa-plus"></i> Add Business
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="fas fa-hotel"></i> Hotels</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <h4>{{ $businessStats['hotels']['count'] }}</h4>
                                                    <small class="text-muted">Active Hotels</small>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <h4>${{ number_format($businessStats['hotels']['revenue']) }}</h4>
                                                    <small class="text-muted">Total Revenue</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary">Manage Hotels</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-ship"></i> Ferries</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <h4>{{ $businessStats['ferries']['count'] }}</h4>
                                                    <small class="text-muted">Active Ferries</small>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <h4>${{ number_format($businessStats['ferries']['revenue']) }}</h4>
                                                    <small class="text-muted">Total Revenue</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-info">Manage Ferries</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="fas fa-star"></i> Theme Parks</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <h4>{{ $businessStats['parks']['count'] }}</h4>
                                                    <small class="text-muted">Active Parks</small>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <h4>${{ number_format($businessStats['parks']['revenue']) }}</h4>
                                                    <small class="text-muted">Total Revenue</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-success">Manage Parks</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0"><i class="fas fa-umbrella-beach"></i> Beach Events</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <h4>{{ $businessStats['beaches']['count'] }}</h4>
                                                    <small class="text-muted">Active Events</small>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <h4>${{ number_format($businessStats['beaches']['revenue']) }}</h4>
                                                    <small class="text-muted">Total Revenue</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-warning">Manage Events</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Management -->
                        <div class="tab-pane fade" id="content" role="tabpanel">
                            <h5>Platform Content Management</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Site Configuration</h6>
                                        </div>
                                        <div class="card-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label class="form-label">Site Name</label>
                                                    <input type="text" class="form-control" value="Tourism Platform">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Maintenance Mode</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                                        <label class="form-check-label" for="maintenanceMode">
                                                            Enable Maintenance Mode
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Platform Commission (%)</label>
                                                    <input type="number" class="form-control" value="5" min="0" max="20" step="0.1">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Settings</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Email Templates</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group">
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="fas fa-envelope"></i> Welcome Email
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="fas fa-check-circle"></i> Booking Confirmation
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="fas fa-times-circle"></i> Booking Cancellation
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="fas fa-key"></i> Password Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Reports -->
                        <div class="tab-pane fade" id="reports" role="tabpanel">
                            <h5>System Analytics & Reports</h5>
                            
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Revenue Analytics</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="revenueChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Quick Reports</h6>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-outline-primary w-100 mb-2">
                                                <i class="fas fa-users"></i> User Activity Report
                                            </button>
                                            <button class="btn btn-outline-success w-100 mb-2">
                                                <i class="fas fa-chart-bar"></i> Business Performance
                                            </button>
                                            <button class="btn btn-outline-info w-100 mb-2">
                                                <i class="fas fa-ticket-alt"></i> Booking Analytics
                                            </button>
                                            <button class="btn btn-outline-warning w-100">
                                                <i class="fas fa-dollar-sign"></i> Financial Summary
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Popular Services</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>Hotel Bookings</span>
                                                <span class="badge bg-primary">{{ $popularServices['hotels'] ?? 0 }}</span>
                                            </div>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-primary" style="width: 65%"></div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>Ferry Tickets</span>
                                                <span class="badge bg-info">{{ $popularServices['ferries'] ?? 0 }}</span>
                                            </div>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-info" style="width: 45%"></div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>Theme Park Tickets</span>
                                                <span class="badge bg-success">{{ $popularServices['parks'] ?? 0 }}</span>
                                            </div>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" style="width: 55%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">System Health</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span>Database Status</span>
                                                <span class="badge bg-success">Online</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span>Storage Usage</span>
                                                <span class="badge bg-warning">78%</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span>API Response Time</span>
                                                <span class="badge bg-success">120ms</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Active Sessions</span>
                                                <span class="badge bg-info">{{ $totalUsers }}</span>
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

<script>
// Initialize charts and functions
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Platform Revenue',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

function filterUsers() {
    // Implementation for user filtering
    console.log('Filtering users...');
}

function viewUser(userId) {
    // Implementation for viewing user details
    console.log('Viewing user:', userId);
}

function editUser(userId) {
    // Implementation for editing user
    console.log('Editing user:', userId);
}

function suspendUser(userId) {
    if (confirm('Are you sure you want to suspend this user?')) {
        // Implementation for suspending user
        console.log('Suspending user:', userId);
    }
}

function exportUsers() {
    // Implementation for exporting users
    window.location.href = '/admin/system/export/users';
}
</script>
@endsection
