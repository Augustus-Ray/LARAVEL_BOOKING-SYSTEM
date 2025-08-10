<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking Cart - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cart-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }
        .cart-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .item-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
        }
        .quantity-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        .price-highlight {
            font-size: 1.1em;
            font-weight: bold;
            color: #28a745;
        }
        .status-pending {
            background: linear-gradient(45deg, #ffc107, #fff3cd);
            color: #856404;
        }
        .status-confirmed {
            background: linear-gradient(45deg, #28a745, #d4edda);
            color: #155724;
        }
        .empty-cart {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
        }
        .cart-summary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
        }
        .btn-pay-now {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-pay-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.4);
        }
        .breadcrumb {
            background: none;
            padding: 0;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: "→";
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-island-tropical me-2"></i>Paradise Island
            </a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('hotel-bookings.index') }}">My Cart</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">My Booking Cart</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold text-primary">
                    <i class="fas fa-shopping-cart me-3"></i>My Booking Cart
                </h1>
                <p class="lead text-muted">Manage all your Paradise Island bookings in one place</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $totalCartValue = 0;
            $pendingCount = 0;
            $confirmedCount = 0;
            
            // Calculate totals from all booking types
            foreach([$bookings, $ferryTickets, $parkTickets, $activityTickets, $beachTickets] as $collection) {
                foreach($collection as $item) {
                    $totalCartValue += $item->total_price ?? $item->price ?? 0;
                    if($item->status === 'pending') $pendingCount++;
                    if($item->status === 'confirmed') $confirmedCount++;
                }
            }
        @endphp

        @if($bookings->count() > 0 || $ferryTickets->count() > 0 || $parkTickets->count() > 0 || $activityTickets->count() > 0 || $beachTickets->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Cart Items
                                    <span class="badge bg-primary ms-2">{{ $bookings->count() + $ferryTickets->count() + $parkTickets->count() + $activityTickets->count() + $beachTickets->count() }}</span>
                                </h5>
                                <div class="text-muted">
                                    <small>
                                        <span class="badge bg-warning text-dark me-1">{{ $pendingCount }} Pending</span>
                                        <span class="badge bg-success">{{ $confirmedCount }} Confirmed</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Hotel Bookings -->
                            @if($bookings->count() > 0)
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-hotel me-2"></i>Hotel Bookings
                                </h6>
                                @foreach($bookings as $booking)
                                    <div class="cart-item p-3 mb-3 {{ $booking->status === 'pending' ? 'status-pending' : 'status-confirmed' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="position-relative">
                                                    @if($booking->hotel && $booking->hotel->image)
                                                        <img src="{{ asset('storage/' . $booking->hotel->image) }}" class="item-image" alt="{{ $booking->hotel->name }}">
                                                    @else
                                                        <div class="item-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-hotel fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <span class="quantity-badge">{{ $booking->guests }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1">{{ $booking->hotel->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - 
                                                    {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                                    ({{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date) }} nights)
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-users me-1"></i>{{ $booking->guests }} Guests
                                                </p>
                                                <small class="text-muted">Ref: {{ $booking->booking_reference }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-{{ $booking->status === 'pending' ? 'warning' : 'success' }} p-2">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="price-highlight">${{ number_format($booking->total_price, 2) }}</div>
                                                <div class="mt-2">
                                                    <a href="{{ route('hotels.show', $booking->hotel) }}" class="btn btn-sm btn-outline-info me-1" title="View Hotel">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($booking->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary me-1 pay-button" 
                                                                data-type="hotel" 
                                                                data-id="{{ $booking->id }}" 
                                                                data-amount="{{ $booking->total_price }}"
                                                                data-reference="{{ $booking->booking_reference }}"
                                                                title="Pay Now">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger cancel-button" 
                                                                data-type="hotel" 
                                                                data-id="{{ $booking->id }}"
                                                                title="Cancel Booking">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr class="my-4">
                            @endif

                            <!-- Ferry Tickets -->
                            @if($ferryTickets->count() > 0)
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-ship me-2"></i>Ferry Tickets
                                </h6>
                                @foreach($ferryTickets as $ticket)
                                    <div class="cart-item p-3 mb-3 {{ $ticket->status === 'pending' ? 'status-pending' : 'status-confirmed' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="position-relative">
                                                    <div class="item-image bg-info d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-ship fa-2x text-white"></i>
                                                    </div>
                                                    <span class="quantity-badge">{{ $ticket->passengers }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1">{{ $ticket->ferry->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-route me-1"></i>
                                                    {{ $ticket->ferry->departure_port }} → {{ $ticket->ferry->arrival_port }}
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($ticket->travel_date)->format('M d, Y') }}
                                                </p>
                                                <small class="text-muted">Ref: {{ $ticket->booking_reference }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-{{ $ticket->status === 'pending' ? 'warning' : 'success' }} p-2">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="price-highlight">${{ number_format($ticket->total_price, 2) }}</div>
                                                <div class="mt-2">
                                                    <a href="{{ route('ferries.show', $ticket->ferry) }}" class="btn btn-sm btn-outline-info me-1" title="View Ferry">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($ticket->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary me-1 pay-button"
                                                                data-type="ferry" 
                                                                data-id="{{ $ticket->id }}" 
                                                                data-amount="{{ $ticket->total_price }}"
                                                                data-reference="{{ $ticket->booking_reference }}"
                                                                title="Pay Now">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger cancel-button" 
                                                                data-type="ferry" 
                                                                data-id="{{ $ticket->id }}"
                                                                title="Cancel Ticket">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr class="my-4">
                            @endif

                            <!-- Park Tickets -->
                            @if($parkTickets->count() > 0)
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-ticket-alt me-2"></i>Theme Park Tickets
                                </h6>
                                @foreach($parkTickets as $ticket)
                                    <div class="cart-item p-3 mb-3 {{ $ticket->status === 'pending' ? 'status-pending' : 'status-confirmed' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="position-relative">
                                                    <div class="item-image bg-success d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-rocket fa-2x text-white"></i>
                                                    </div>
                                                    <span class="quantity-badge">{{ $ticket->visitors }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1">{{ $ticket->themePark->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($ticket->visit_date)->format('M d, Y') }}
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-users me-1"></i>{{ $ticket->visitors }} Visitors
                                                </p>
                                                <small class="text-muted">Ref: {{ $ticket->ticket_reference }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-{{ $ticket->status === 'pending' ? 'warning' : 'success' }} p-2">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="price-highlight">${{ number_format($ticket->total_price, 2) }}</div>
                                                <div class="mt-2">
                                                    <a href="{{ route('theme-parks.show', $ticket->themePark) }}" class="btn btn-sm btn-outline-info me-1" title="View Theme Park">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($ticket->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary me-1 pay-button"
                                                                data-type="park" 
                                                                data-id="{{ $ticket->id }}" 
                                                                data-amount="{{ $ticket->total_price }}"
                                                                data-reference="{{ $ticket->ticket_reference }}"
                                                                title="Pay Now">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger cancel-button" 
                                                                data-type="park" 
                                                                data-id="{{ $ticket->id }}"
                                                                title="Cancel Ticket">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr class="my-4">
                            @endif

                            <!-- Activity Tickets -->
                            @if($activityTickets->count() > 0)
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-gamepad me-2"></i>Activity Tickets
                                </h6>
                                @foreach($activityTickets as $ticket)
                                    <div class="cart-item p-3 mb-3 {{ $ticket->status === 'pending' ? 'status-pending' : 'status-confirmed' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="position-relative">
                                                    <div class="item-image bg-warning d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-gamepad fa-2x text-white"></i>
                                                    </div>
                                                    <span class="quantity-badge">{{ $ticket->participants }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1">{{ $ticket->parkActivity->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $ticket->parkActivity->themePark->name }}
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($ticket->scheduled_time)->format('M d, Y g:i A') }}
                                                </p>
                                                <small class="text-muted">Ref: {{ $ticket->activity_reference }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-{{ $ticket->status === 'pending' ? 'warning' : 'success' }} p-2">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="price-highlight">${{ number_format($ticket->total_price, 2) }}</div>
                                                <div class="mt-2">
                                                    <a href="{{ route('theme-parks.show', $ticket->parkActivity->themePark) }}" class="btn btn-sm btn-outline-info me-1" title="View Theme Park">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($ticket->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary me-1 pay-button"
                                                                data-type="activity" 
                                                                data-id="{{ $ticket->id }}" 
                                                                data-amount="{{ $ticket->total_price }}"
                                                                data-reference="{{ $ticket->activity_reference }}"
                                                                title="Pay Now">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger cancel-button" 
                                                                data-type="activity" 
                                                                data-id="{{ $ticket->id }}"
                                                                title="Cancel Activity">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr class="my-4">
                            @endif

                            <!-- Beach Event Tickets -->
                            @if($beachTickets->count() > 0)
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-umbrella-beach me-2"></i>Beach Event Tickets
                                </h6>
                                @foreach($beachTickets as $ticket)
                                    <div class="cart-item p-3 mb-3 {{ $ticket->status === 'pending' ? 'status-pending' : 'status-confirmed' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="position-relative">
                                                    <div class="item-image bg-info d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-umbrella-beach fa-2x text-white"></i>
                                                    </div>
                                                    <span class="quantity-badge">{{ $ticket->participants }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1">{{ $ticket->beachEvent->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $ticket->beachEvent->location }}
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $ticket->beachEvent->start_time->format('M d, Y g:i A') }}
                                                </p>
                                                <small class="text-muted">Ref: {{ $ticket->ticket_reference }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-{{ $ticket->status === 'pending' ? 'warning' : 'success' }} p-2">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="price-highlight">${{ number_format($ticket->total_price, 2) }}</div>
                                                <div class="mt-2">
                                                    <a href="{{ route('beach-events.show', $ticket->beachEvent) }}" class="btn btn-sm btn-outline-info me-1" title="View Beach Event">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($ticket->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary me-1 pay-button"
                                                                data-type="beach" 
                                                                data-id="{{ $ticket->id }}" 
                                                                data-amount="{{ $ticket->total_price }}"
                                                                data-reference="{{ $ticket->ticket_reference }}"
                                                                title="Pay Now">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger cancel-button" 
                                                                data-type="beach" 
                                                                data-id="{{ $ticket->id }}"
                                                                title="Cancel Ticket">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary p-4 mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-calculator me-2"></i>Cart Summary
                        </h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Items:</span>
                            <span class="fw-bold">{{ $bookings->count() + $ferryTickets->count() + $parkTickets->count() + $activityTickets->count() + $beachTickets->count() }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending Payment:</span>
                            <span class="fw-bold">{{ $pendingCount }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Confirmed:</span>
                            <span class="fw-bold">{{ $confirmedCount }}</span>
                        </div>
                        
                        <hr class="border-light opacity-25">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <h6 class="fw-bold">Total Value:</h6>
                            <h6 class="fw-bold">${{ number_format($totalCartValue, 2) }}</h6>
                        </div>

                        @if($pendingCount > 0)
                            <button class="btn btn-pay-now w-100 mb-3" id="payAllButton">
                                <i class="fas fa-credit-card me-2"></i>
                                Pay All Pending ({{ $pendingCount }} items)
                            </button>
                        @endif
                        
                        <div class="text-center">
                            <a href="{{ route('hotels.index') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-plus me-1"></i>Add More
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
                                <i class="fas fa-user me-1"></i>Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-hotel me-2"></i>Browse Hotels
                                </a>
                                @if($bookings->where('status', 'confirmed')->count() > 0)
                                    <a href="{{ route('ferries.index') }}" class="btn btn-outline-info">
                                        <i class="fas fa-ship me-2"></i>Book Ferry
                                    </a>
                                    <a href="{{ route('theme-parks.index') }}" class="btn btn-outline-success">
                                        <i class="fas fa-ticket-alt me-2"></i>Theme Parks
                                    </a>
                                @endif
                                <a href="{{ route('beach-events.index') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-umbrella-beach me-2"></i>Beach Events
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="empty-cart text-center">
                <div>
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Start your Paradise Island adventure by booking your first service!</p>
                    <a href="{{ route('hotels.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-hotel me-2"></i>Browse Hotels
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">
                        <i class="fas fa-credit-card me-2"></i>Secure Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-3">Payment Information</h6>
                            <form id="paymentForm">
                                <div class="mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" value="4111 1111 1111 1111" readonly>
                                    <small class="text-muted">Demo card number (pre-filled)</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiryDate" value="12/26" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" value="123" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control" id="cardholderName" value="John Demo User" readonly>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <h6>Order Summary</h6>
                                <div id="paymentSummary">
                                    <!-- Dynamic content will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmPayment">
                        <i class="fas fa-lock me-2"></i>Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            let currentPaymentData = {};

            // Individual payment buttons
            document.querySelectorAll('.pay-button').forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const id = this.dataset.id;
                    const amount = this.dataset.amount;
                    const reference = this.dataset.reference;

                    currentPaymentData = {
                        type: type,
                        ids: [id],
                        totalAmount: amount,
                        isMultiple: false
                    };

                    document.getElementById('paymentSummary').innerHTML = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>${type.charAt(0).toUpperCase() + type.slice(1)} Booking:</span>
                            <strong>$${parseFloat(amount).toFixed(2)}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Reference:</small>
                            <small class="text-muted">${reference}</small>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong>$${parseFloat(amount).toFixed(2)}</strong>
                        </div>
                    `;

                    paymentModal.show();
                });
            });

            // Pay all button
            document.getElementById('payAllButton')?.addEventListener('click', function() {
                const pendingItems = [];
                let totalAmount = 0;

                // Collect all pending items
                document.querySelectorAll('.pay-button').forEach(button => {
                    const type = button.dataset.type;
                    const id = button.dataset.id;
                    const amount = parseFloat(button.dataset.amount);
                    
                    pendingItems.push({ type, id });
                    totalAmount += amount;
                });

                currentPaymentData = {
                    type: 'multiple',
                    items: pendingItems,
                    totalAmount: totalAmount,
                    isMultiple: true
                };

                document.getElementById('paymentSummary').innerHTML = `
                    <div class="mb-2">
                        <strong>Multiple Items (${pendingItems.length})</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>$${totalAmount.toFixed(2)}</strong>
                    </div>
                `;

                paymentModal.show();
            });

            // Confirm payment
            document.getElementById('confirmPayment').addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                // Simulate payment processing
                setTimeout(() => {
                    if (currentPaymentData.isMultiple) {
                        // Process multiple payments
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("user.booking.pay-all") }}';
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        // Send booking data as JSON string
                        const bookingsInput = document.createElement('input');
                        bookingsInput.type = 'hidden';
                        bookingsInput.name = 'bookings';
                        bookingsInput.value = JSON.stringify(currentPaymentData.items);
                        form.appendChild(bookingsInput);

                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Process single payment
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/user/booking/${currentPaymentData.type}/${currentPaymentData.ids[0]}/pay`;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }, 1500);
            });
        });

        // Handle cancel buttons
        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                const id = this.dataset.id;
                
                if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/user/booking/${type}/${id}/cancel`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
