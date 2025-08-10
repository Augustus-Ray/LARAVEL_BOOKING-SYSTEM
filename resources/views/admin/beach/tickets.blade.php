@extends('layouts.admin')

@section('title', 'Beach Event Tickets Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Beach Event Tickets</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search tickets...">
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
                                <th>Event Date</th>
                                <th>Participants</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Beach Event</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>{{ $ticket->user->email }}</td>
                                <td>{{ $ticket->event_date->format('M d, Y') }}</td>
                                <td>{{ $ticket->participants }}</td>
                                <td>${{ number_format($ticket->total_price, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ticket->payment_status === 'paid' ? 'success' : ($ticket->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($ticket->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ticket->beachEvent)
                                        <span class="badge badge-info">{{ $ticket->beachEvent->name }}</span>
                                    @else
                                        <span class="text-muted">No event</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($ticket->payment_status === 'pending' && $ticket->status !== 'cancelled')
                                            <form action="{{ route('admin.beach.tickets.mark-paid', $ticket->id) }}" method="POST" style="display: inline;" onsubmit="console.log('Submitting mark-paid form for beach ticket:', {{ $ticket->id }}); return confirm('Mark this ticket as paid?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Paid">
                                                    <i class="fas fa-check-circle"></i> Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($ticket->status !== 'cancelled')
                                            <form action="{{ route('admin.beach.tickets.cancel', $ticket->id) }}" method="POST" style="display: inline;" onsubmit="console.log('Submitting cancel form for beach ticket:', {{ $ticket->id }}); return confirm('Cancel this ticket?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Cancel Ticket">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewTicketDetails({{ $ticket->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No tickets found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Details Modal -->
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Beach Event Ticket Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ticketDetailsContent">
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
function viewTicketDetails(ticketId) {
    $('#ticketDetailsModal').modal('show');
    $('#ticketDetailsContent').html('<p>Loading ticket details...</p>');
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
    console.log('Admin beach tickets page loaded');
    console.log('Current user type: {{ Auth::guard("admin")->user()->business_type ?? "not logged in" }}');
    
    $('form').on('submit', function(e) {
        console.log('Form being submitted:', this);
        console.log('Action URL:', $(this).attr('action'));
        console.log('CSRF Token:', $(this).find('input[name="_token"]').val());
    });
});
</script>
@endsection
