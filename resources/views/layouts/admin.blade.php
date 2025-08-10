<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Tourism Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .navbar-brand {
            font-weight: 600;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .badge {
            font-size: 0.75em;
        }
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            border-bottom-color: #0d6efd;
            background: none;
        }
        .progress {
            height: 8px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-shield-alt"></i> Tourism Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    
                    @if(Auth::guard('admin')->user()->business_type === 'hotel')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.hotel.bookings') }}">
                            <i class="fas fa-calendar-check"></i> Hotel Bookings
                        </a>
                    </li>
                    @elseif(Auth::guard('admin')->user()->business_type === 'ferry')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ferry.tickets') }}">
                            <i class="fas fa-ticket-alt"></i> Ferry Tickets
                        </a>
                    </li>
                    @elseif(Auth::guard('admin')->user()->business_type === 'theme_park')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.park.tickets') }}">
                            <i class="fas fa-ticket-alt"></i> Park Tickets
                        </a>
                    </li>
                    @elseif(Auth::guard('admin')->user()->business_type === 'beach')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.beach.tickets') }}">
                            <i class="fas fa-ticket-alt"></i> Beach Tickets
                        </a>
                    </li>
                    @elseif(Auth::guard('admin')->user()->business_type === 'system')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.system.dashboard') }}">
                            <i class="fas fa-cogs"></i> System Admin
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.manage-bookings') }}">
                            <i class="fas fa-calendar-check"></i> Bookings
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Debug Info -->
                    @auth('admin')
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            <small class="text-light">
                                <i class="fas fa-user-circle"></i> 
                                {{ Auth::guard('admin')->user()->business_type }} | 
                                {{ Auth::guard('admin')->user()->business_name }}
                            </small>
                        </span>
                    </li>
                    @endauth
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::guard('admin')->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle"></i> Profile
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container-fluid">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
