<?php

namespace App\Http\Controllers;

use App\Models\ActivityTicket;
use App\Models\ParkActivity;
use App\Models\ParkTicket;
use Illuminate\Http\Request;

class ActivityTicketController extends Controller
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
    public function create(ParkActivity $parkActivity, Request $request)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking activities.');
        }

        // Check if user has a valid park ticket for this theme park
        $parkTicket = auth()->user()->parkTickets()
            ->where('theme_park_id', $parkActivity->theme_park_id)
            ->where('status', 'confirmed')
            ->where('visit_date', '>=', now()->toDateString())
            ->first();

        if (!$parkTicket) {
            return redirect()->route('theme-parks.show', $parkActivity->themePark)
                ->with('error', 'You need a valid theme park ticket before booking activities.');
        }

        $participants = $request->get('participants', 1);
        $scheduled_time = $request->get('scheduled_time', '');
        
        return view('activities.book', compact('parkActivity', 'parkTicket', 'participants', 'scheduled_time'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ParkActivity $parkActivity)
    {
        // Check if user has valid hotel booking
        if (!auth()->user()->hasValidHotelBooking()) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking before booking activities.');
        }

        // Check if user has a valid park ticket for this theme park
        $parkTicket = auth()->user()->parkTickets()
            ->where('theme_park_id', $parkActivity->theme_park_id)
            ->where('status', 'confirmed')
            ->where('visit_date', '>=', now()->toDateString())
            ->first();

        if (!$parkTicket) {
            return redirect()->route('theme-parks.show', $parkActivity->themePark)
                ->with('error', 'You need a valid theme park ticket before booking activities.');
        }

        $validated = $request->validate([
            'participants' => 'required|integer|min:1|max:' . $parkActivity->capacity_per_session,
            'scheduled_time' => 'required|date|after:now',
        ]);

        $totalPrice = $parkActivity->price * $validated['participants'];

        // Create activity ticket
        $activityTicket = ActivityTicket::create([
            'user_id' => auth()->id(),
            'park_activity_id' => $parkActivity->id,
            'park_ticket_id' => $parkTicket->id,
            'scheduled_time' => $validated['scheduled_time'],
            'participants' => $validated['participants'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'activity_reference' => 'ACT-' . strtoupper(uniqid()),
        ]);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Activity added to cart! Please complete payment to confirm your booking. Reference: ' . $activityTicket->activity_reference);
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityTicket $activityTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityTicket $activityTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityTicket $activityTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityTicket $activityTicket)
    {
        //
    }

    /**
     * Cancel an activity ticket booking.
     */
    public function cancel(ActivityTicket $activityTicket)
    {
        // Check if user owns this ticket
        if ($activityTicket->user_id !== auth()->id()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'You can only cancel your own bookings.');
        }

        // Check if ticket is already cancelled
        if ($activityTicket->status === 'cancelled') {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'This activity is already cancelled.');
        }

        // Check if scheduled time is not within 1 hour
        if ($activityTicket->scheduled_time <= now()->addHour()) {
            return redirect()->route('hotel-bookings.index')
                ->with('error', 'Cannot cancel activities less than 1 hour before the scheduled time.');
        }

        // Update status to cancelled
        $activityTicket->update(['status' => 'cancelled']);

        return redirect()->route('hotel-bookings.index')
            ->with('success', 'Activity cancelled successfully. Reference: ' . $activityTicket->activity_reference);
    }
}
