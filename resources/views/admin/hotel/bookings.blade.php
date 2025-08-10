@extends('layouts.admin')

@section('title', 'Hotel Bookings Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hotel Bookings</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search bookings...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Rooms</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->user->email }}</td>
                                <td>{{ $booking->check_in->format('M d, Y') }}</td>
                                <td>{{ $booking->check_out->format('M d, Y') }}</td>
                                <td>{{ $booking->rooms }}</td>
                                <td>${{ number_format($booking->total_price, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($booking->payment_status === 'pending' && $booking->status !== 'cancelled')
                                            <form action="{{ route('admin.hotel.bookings.mark-paid', $booking->id) }}" method="POST" style="display: inline;" onsubmit="console.log('Submitting mark-paid form for booking:', {{ $booking->id }}); return confirm('Mark this booking as paid?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Paid">
                                                    <i class="fas fa-check-circle"></i> Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($booking->status !== 'cancelled')
                                            <form action="{{ route('admin.hotel.bookings.cancel', $booking->id) }}" method="POST" style="display: inline;" onsubmit="console.log('Submitting cancel form for booking:', {{ $booking->id }}); return confirm('Cancel this booking?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Cancel Booking">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewBookingDetails({{ $booking->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No bookings found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function viewBookingDetails(bookingId) {
    // You can implement AJAX call to fetch booking details
    $('#bookingDetailsModal').modal('show');
    $('#bookingDetailsContent').html('<p>Loading booking details...</p>');
    
    // Example AJAX call (you'll need to create the route)
    // $.get('/admin/hotel/bookings/' + bookingId + '/details', function(data) {
    //     $('#bookingDetailsContent').html(data);
    // });
}

// Search functionality
$('#searchInput').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

// Debug: Log form submissions and authentication status
$(document).ready(function() {
    console.log('Admin bookings page loaded');
    console.log('Current user type: {{ Auth::guard("admin")->user()->business_type ?? "not logged in" }}');
    
    $('form').on('submit', function(e) {
        console.log('Form being submitted:', this);
        console.log('Action URL:', $(this).attr('action'));
        console.log('CSRF Token:', $(this).find('input[name="_token"]').val());
    });
});
</script>
@endsection
