<?php

namespace App\Http\Controllers;

use App\Models\ParkTicket;
use App\Models\ThemePark;
use Illuminate\Http\Request;

class ParkTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ThemePark $themePark, Request $request)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking theme park tickets.');
        }

        $visitors = $request->get('visitors', 1);
        $visit_date = $request->get('visit_date', date('Y-m-d'));
        
        return view('theme-parks.book', compact('themePark', 'visitors', 'visit_date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ThemePark $themePark)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking theme park tickets.');
        }

        $validated = $request->validate([
            'visitors' => 'required|integer|min:1|max:10',
            'visit_date' => 'required|date|after_or_equal:today',
        ]);

        $totalPrice = $themePark->entry_price * $validated['visitors'];

        // Get the user's most recent confirmed hotel booking
        $hotelBooking = auth()->user()->hotelBookings()
            ->where('status', 'confirmed')
            ->latest()
            ->first();

        // Create park ticket
        $parkTicket = ParkTicket::create([
            'user_id' => auth()->id(),
            'theme_park_id' => $themePark->id,
            'hotel_booking_id' => $hotelBooking->id,
            'visit_date' => $validated['visit_date'],
            'visitors' => $validated['visitors'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'ticket_reference' => 'PARK-' . strtoupper(uniqid()),
        ]);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Theme park tickets added to cart! Reference: ' . $parkTicket->ticket_reference . ' - Complete payment in your cart.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkTicket $parkTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkTicket $parkTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParkTicket $parkTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkTicket $parkTicket)
    {
        //
    }

    /**
     * Cancel a park ticket booking.
     */
    public function cancel(ParkTicket $parkTicket)
    {
        // Check if user owns this ticket
        if ($parkTicket->user_id !== auth()->id()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'You can only cancel your own bookings.');
        }

        // Check if ticket is already cancelled
        if ($parkTicket->status === 'cancelled') {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'This ticket is already cancelled.');
        }

        // Check if visit date is not today or in the past
        if ($parkTicket->visit_date <= now()->toDateString()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'Cannot cancel tickets for today or past dates.');
        }

        // Cancel related activity tickets first
        $parkTicket->activityTickets()->where('status', 'confirmed')->update(['status' => 'cancelled']);

        // Update status to cancelled
        $parkTicket->update(['status' => 'cancelled']);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Theme park ticket cancelled successfully. Any related activity tickets have also been cancelled. Reference: ' . $parkTicket->ticket_reference);
    }
}
