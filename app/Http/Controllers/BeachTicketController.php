<?php

namespace App\Http\Controllers;

use App\Models\BeachTicket;
use App\Models\BeachEvent;
use Illuminate\Http\Request;

class BeachTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beachTickets = auth()->user()->beachTickets()->with('beachEvent')->latest()->get();
        return view('beach-tickets.index', compact('beachTickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(BeachEvent $beachEvent, Request $request)
    {
        $participants = $request->get('participants', 1);
        
        return view('beach-events.book', compact('beachEvent', 'participants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BeachEvent $beachEvent)
    {
        $validated = $request->validate([
            'participants' => 'required|integer|min:1|max:10',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'special_requirements' => 'nullable|string|max:500'
        ]);

        // Check if event has enough capacity
        $existingBookings = BeachTicket::where('beach_event_id', $beachEvent->id)
            ->where('status', '!=', 'cancelled')
            ->sum('participants');
            
        if ($existingBookings + $validated['participants'] > $beachEvent->capacity) {
            return back()->with('error', 'Not enough capacity available for this event.');
        }

        // Calculate total price
        $totalPrice = $beachEvent->price * $validated['participants'];

        // Create beach ticket
        $beachTicket = BeachTicket::create([
            'user_id' => auth()->id(),
            'beach_event_id' => $beachEvent->id,
            'participants' => $validated['participants'],
            'total_price' => $totalPrice,
            'ticket_reference' => 'BE' . str_pad($beachEvent->id, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -6)),
            'special_requests' => $validated['special_requirements'],
            'status' => 'pending'
        ]);

        return redirect()->route('hotel-bookings.index')
            ->with('success', "Beach event added to cart! Please complete payment to confirm your booking. Reference: {$beachTicket->ticket_reference}");
    }

    /**
     * Display the specified resource.
     */
    public function show(BeachTicket $beachTicket)
    {
        $this->authorize('view', $beachTicket);
        return view('beach-tickets.show', compact('beachTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeachTicket $beachTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BeachTicket $beachTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeachTicket $beachTicket)
    {
        $this->authorize('delete', $beachTicket);
        
        $beachTicket->delete();
        
        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Beach event booking cancelled successfully.');
    }
}
