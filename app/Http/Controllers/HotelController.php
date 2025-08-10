<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::where('is_active', true)
            ->with('owner')
            ->paginate(12);

        return view('hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Hotel::class);
        return view('hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Hotel::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|url',
            'contact_info' => 'nullable|string',
        ]);

        $validated['owner_id'] = auth()->id();
        $validated['available_rooms'] = $validated['total_rooms'];

        $hotel = Hotel::create($validated);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Hotel created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('owner', 'bookings.user');
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        $this->authorize('update', $hotel);
        return view('hotels.edit', compact('hotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|url',
            'contact_info' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Hotel updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);
        
        $hotel->delete();
        
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted successfully!');
    }
}
