<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #8e44ad 0%, #663399 100%);
        }
        .card-stats {
            border-left: 4px solid #8e44ad;
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link.active {
            background-color: #8e44ad !important;
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
                        <a class="nav-link active" href="{{ route('super-admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.manage-admins') }}">
                            <i class="fas fa-users-cog me-2"></i>Manage Admins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.bookings') }}">
                            <i class="fas fa-list me-2"></i>All Bookings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Super Admin Dashboard</h2>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h4>{{ $stats['total_users'] }}</h4>
                                <p class="text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-user-shield fa-2x text-warning mb-2"></i>
                                <h4>{{ $stats['total_admins'] }}</h4>
                                <p class="text-muted mb-0">Total Admins</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
                                <h4>{{ $stats['hotel_bookings'] + $stats['ferry_tickets'] + $stats['park_tickets'] + $stats['activity_tickets'] + $stats['beach_tickets'] }}</h4>
                                <p class="text-muted mb-0">Total Bookings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                                <h4>${{ number_format($stats['total_revenue'], 0) }}</h4>
                                <p class="text-muted mb-0">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Booking Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <i class="fas fa-hotel fa-2x text-primary mb-2"></i>
                                            <h5>{{ $stats['hotel_bookings'] }}</h5>
                                            <p class="text-muted">Hotels</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <i class="fas fa-ship fa-2x text-info mb-2"></i>
                                            <h5>{{ $stats['ferry_tickets'] }}</h5>
                                            <p class="text-muted">Ferries</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
                                            <h5>{{ $stats['park_tickets'] }}</h5>
                                            <p class="text-muted">Parks</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <i class="fas fa-play fa-2x text-warning mb-2"></i>
                                            <h5>{{ $stats['activity_tickets'] }}</h5>
                                            <p class="text-muted">Activities</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <i class="fas fa-umbrella-beach fa-2x text-danger mb-2"></i>
                                            <h5>{{ $stats['beach_tickets'] }}</h5>
                                            <p class="text-muted">Beach Events</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('super-admin.manage-admins') }}" class="btn btn-primary btn-lg w-100 mb-3">
                                            <i class="fas fa-users-cog me-2"></i>Manage Administrators
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('super-admin.bookings') }}" class="btn btn-success btn-lg w-100 mb-3">
                                            <i class="fas fa-list me-2"></i>View All Bookings
                                        </a>
                                    </div>
                                </div>
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
