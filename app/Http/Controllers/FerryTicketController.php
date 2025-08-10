<?php

namespace App\Http\Controllers;

use App\Models\FerryTicket;
use App\Models\Ferry;
use Illuminate\Http\Request;

class FerryTicketController extends Controller
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
    public function create(Ferry $ferry, Request $request)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking ferry tickets.');
        }

        $passengers = $request->get('passengers', 1);
        $travel_date = $request->get('travel_date', date('Y-m-d'));
        
        return view('ferries.book', compact('ferry', 'passengers', 'travel_date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ferry $ferry)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking ferry tickets.');
        }

        $validated = $request->validate([
            'passengers' => 'required|integer|min:1|max:10',
            'travel_date' => 'required|date|after_or_equal:today',
            'passenger_names' => 'required|array',
            'passenger_names.*' => 'required|string|max:255',
        ]);

        $totalPrice = $ferry->price * $validated['passengers'];

        // Get the user's most recent confirmed hotel booking
        $hotelBooking = auth()->user()->hotelBookings()
            ->where('status', 'confirmed')
            ->latest()
            ->first();

        // Create ferry ticket
        $ferryTicket = FerryTicket::create([
            'user_id' => auth()->id(),
            'ferry_id' => $ferry->id,
            'hotel_booking_id' => $hotelBooking->id,
            'travel_date' => $validated['travel_date'],
            'passengers' => $validated['passengers'],
            'passenger_names' => json_encode($validated['passenger_names']),
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_reference' => 'FER-' . strtoupper(uniqid()),
        ]);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Ferry tickets added to cart! Reference: ' . $ferryTicket->booking_reference . ' - Complete payment in your cart.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FerryTicket $ferryTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FerryTicket $ferryTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FerryTicket $ferryTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FerryTicket $ferryTicket)
    {
        //
    }

    /**
     * Cancel a ferry ticket booking.
     */
    public function cancel(FerryTicket $ferryTicket)
    {
        // Check if user owns this ticket
        if ($ferryTicket->user_id !== auth()->id()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'You can only cancel your own bookings.');
        }

        // Check if ticket is already cancelled
        if ($ferryTicket->status === 'cancelled') {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'This ticket is already cancelled.');
        }

        // Check if travel date is not today or in the past
        if ($ferryTicket->travel_date <= now()->toDateString()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'Cannot cancel tickets for today or past dates.');
        }

        // Update status to cancelled
        $ferryTicket->update(['status' => 'cancelled']);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Ferry ticket cancelled successfully. Reference: ' . $ferryTicket->booking_reference);
    }
}
