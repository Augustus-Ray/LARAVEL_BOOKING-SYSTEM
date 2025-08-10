<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Auth::user()->hotelBookings()->with('hotel')->latest()->get();
        $ferryTickets = Auth::user()->ferryTickets()->with('ferry')->latest()->get();
        $parkTickets = Auth::user()->parkTickets()->with('themePark')->latest()->get();
        $activityTickets = Auth::user()->activityTickets()->with('parkActivity.themePark')->latest()->get();
        $beachTickets = Auth::user()->beachTickets()->with('beachEvent')->latest()->get();
        return view('hotel-bookings.index', compact('bookings', 'ferryTickets', 'parkTickets', 'activityTickets', 'beachTickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hotel $hotel)
    {
        return view('hotel-bookings.create', compact('hotel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Hotel $hotel)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
            'rooms' => 'required|integer|min:1|max:5',
            'room_type' => 'required|string|in:standard,deluxe,suite,presidential',
        ]);

        // Check if user already has an active booking at this hotel
        $existingBooking = Auth::user()->hotelBookings()
            ->where('hotel_id', $hotel->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existingBooking) {
            return redirect()->route('hotels.show', $hotel)
                ->with('error', 'You already have an active booking at this hotel.');
        }

        // Check if hotel has enough available rooms
        if ($hotel->available_rooms < $request->rooms) {
            return redirect()->route('hotels.show', $hotel)
                ->with('error', 'Sorry, not enough rooms available. Only ' . $hotel->available_rooms . ' rooms left.');
        }

        // Calculate total price
        $checkIn = new \DateTime($request->check_in);
        $checkOut = new \DateTime($request->check_out);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPrice = $nights * $hotel->price_per_night * $request->rooms;

        // Create booking with pending status (requires payment)
        $booking = HotelBooking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $hotel->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'rooms' => $request->rooms,
            'guests' => $request->guests,
            'room_type' => $request->room_type,
            'total_price' => $totalPrice,
            'status' => 'pending', // Start as pending, becomes confirmed after payment
            'special_requests' => $request->special_requests ?? null,
        ]);

        // Update hotel available rooms (temporarily reserve)
        $hotel->decrement('available_rooms', $request->rooms);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Hotel added to your cart! Please complete payment to confirm your booking.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelBooking $hotelBooking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelBooking $hotelBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelBooking $hotelBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelBooking $hotelBooking)
    {
        //
    }

    /**
     * Cancel a hotel booking.
     */
    public function cancel(HotelBooking $hotelBooking)
    {
        // Check if user owns this booking
        if ($hotelBooking->user_id !== auth()->id()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'You can only cancel your own bookings.');
        }

        // Check if booking is already cancelled
        if ($hotelBooking->status === 'cancelled') {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'This booking is already cancelled.');
        }

        // Check if check-in date is not today or in the past
        if ($hotelBooking->check_in <= now()->toDateString()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'Cannot cancel bookings for today or past dates.');
        }

        // Cancel related ferry tickets first
        $hotelBooking->ferryTickets()->where('status', 'confirmed')->update(['status' => 'cancelled']);

        // Update hotel available rooms (add back the cancelled rooms)
        $hotelBooking->hotel->increment('available_rooms', $hotelBooking->rooms);

        // Update booking status to cancelled
        $hotelBooking->update(['status' => 'cancelled']);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Hotel booking cancelled successfully. Any related ferry tickets have also been cancelled.');
    }
}
