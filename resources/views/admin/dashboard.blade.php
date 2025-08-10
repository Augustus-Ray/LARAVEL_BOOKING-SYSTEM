@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2>{{ $admin->business_name ?? 'Admin Dashboard' }}</h2>
                            <p class="text-muted mb-0">{{ ucfirst(str_replace('_', ' ', $admin->business_type ?? 'general')) }} Dashboard</p>
                        </div>
                        <div>
                            @if($admin->business_type === 'hotel')
                                <a href="{{ route('admin.hotel.bookings') }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-check me-2"></i>Hotel Bookings
                                </a>
                            @elseif($admin->business_type === 'ferry')
                                <a href="{{ route('admin.ferry.operations') }}" class="btn btn-primary">
                                    <i class="fas fa-ship me-2"></i>Ferry Operations
                                </a>
                            @elseif($admin->business_type === 'theme_park')
                                <a href="{{ route('admin.park.tickets') }}" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt me-2"></i>Park Tickets
                                </a>
                            @elseif($admin->business_type === 'beach')
                                <a href="{{ route('admin.beach.tickets') }}" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt me-2"></i>Beach Tickets
                                </a>
                            @elseif($admin->business_type === 'system')
                                <a href="{{ route('admin.system.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-cogs me-2"></i>System Admin
                                </a>
                            @endif
                            <a href="{{ route('admin.manage-bookings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>All Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    @if($admin->business_type === 'hotel' || $admin->business_type === 'general' || $admin->business_type === 'system')
                    <div class="col-md-3 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-hotel fa-2x text-primary mb-2"></i>
                                <h4>{{ $stats['hotel_bookings'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Hotel Bookings</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($admin->business_type === 'ferry' || $admin->business_type === 'general' || $admin->business_type === 'system')
                    <div class="col-md-3 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-ship fa-2x text-info mb-2"></i>
                                <h4>{{ $stats['ferry_tickets'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Ferry Tickets</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($admin->business_type === 'theme_park' || $admin->business_type === 'general' || $admin->business_type === 'system')
                    <div class="col-md-3 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
                                <h4>{{ $stats['park_tickets'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Park Tickets</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($admin->business_type === 'beach' || $admin->business_type === 'general' || $admin->business_type === 'system')
                    <div class="col-md-3 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-umbrella-beach fa-2x text-warning mb-2"></i>
                                <h4>{{ $stats['beach_tickets'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Beach Events</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Recent Bookings -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-clock me-2"></i>Recent Bookings Overview</h5>
                            </div>
                            <div class="card-body">
                                @if(!empty($recent_bookings) && is_array($recent_bookings))
                                    @php $hasAnyBookings = false; @endphp
                                    @foreach($recent_bookings as $type => $bookings)
                                        @if(!empty($bookings) && $bookings->count() > 0)
                                            @php $hasAnyBookings = true; @endphp
                                            <div class="mb-4">
                                                <h6 class="text-capitalize border-bottom pb-2 mb-3">
                                                    <i class="fas fa-{{ $type === 'hotels' ? 'hotel' : ($type === 'ferries' ? 'ship' : ($type === 'parks' ? 'ticket-alt' : 'umbrella-beach')) }} me-2"></i>
                                                    {{ ucfirst($type) }} ({{ $bookings->count() }})
                                                </h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Customer</th>
                                                                <th>Details</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($bookings->take(5) as $booking)
                                                            <tr>
                                                                <td>
                                                                    <div>
                                                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong><br>
                                                                        <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if($type === 'hotels')
                                                                        <div>
                                                                            {{ $booking->hotel->name ?? 'N/A' }}<br>
                                                                            <small class="text-muted">
                                                                                {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d') : 'N/A' }} - 
                                                                                {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}
                                                                            </small>
                                                                        </div>
                                                                    @elseif($type === 'ferries')
                                                                        <div>
                                                                            {{ $booking->ferry->name ?? 'N/A' }}<br>
                                                                            <small class="text-muted">
                                                                                {{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('M d, Y') : 'N/A' }}
                                                                                {{ $booking->departure_time ? $booking->departure_time : '' }}
                                                                            </small>
                                                                        </div>
                                                                    @elseif($type === 'parks')
                                                                        <div>
                                                                            {{ $booking->themePark->name ?? 'Theme Park' }}<br>
                                                                            <small class="text-muted">
                                                                                {{ $booking->visit_date ? \Carbon\Carbon::parse($booking->visit_date)->format('M d, Y') : 'N/A' }}
                                                                            </small>
                                                                        </div>
                                                                    @else
                                                                        <div>
                                                                            {{ $booking->beachEvent->name ?? 'Beach Event' }}<br>
                                                                            <small class="text-muted">
                                                                                {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') : 'N/A' }}
                                                                            </small>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <strong>
                                                                        ${{ number_format($booking->total_price ?? $booking->price ?? 0, 2) }}
                                                                    </strong>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ ($booking->status ?? 'pending') === 'confirmed' ? 'success' : (($booking->status ?? 'pending') === 'cancelled' ? 'danger' : 'warning') }}">
                                                                        {{ ucfirst($booking->status ?? 'pending') }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'N/A' }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    @if(!$hasAnyBookings)
                                        <div class="text-center py-5">
                                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Recent Bookings</h5>
                                            <p class="text-muted">No bookings found for your business type. Start promoting your services!</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Welcome to Your Dashboard</h5>
                                        <p class="text-muted">Your booking data will appear here once customers start making reservations.</p>
                                        @if($admin->business_type !== 'system')
                                            <div class="mt-3">
                                                @if($admin->business_type === 'hotel')
                                                    <a href="{{ route('admin.hotel.bookings') }}" class="btn btn-primary">
                                                        <i class="fas fa-calendar-check me-2"></i>View Hotel Bookings
                                                    </a>
                                                @elseif($admin->business_type === 'ferry')
                                                    <a href="{{ route('admin.ferry.tickets') }}" class="btn btn-primary">
                                                        <i class="fas fa-ticket-alt me-2"></i>Ferry Tickets
                                                    </a>
                                                @elseif($admin->business_type === 'theme_park')
                                                    <a href="{{ route('admin.park.tickets') }}" class="btn btn-primary">
                                                        <i class="fas fa-ticket-alt me-2"></i>Park Tickets
                                                    </a>
                                                @elseif($admin->business_type === 'beach')
                                                    <a href="{{ route('admin.beach.tickets') }}" class="btn btn-primary">
                                                        <i class="fas fa-ticket-alt me-2"></i>Beach Tickets
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
