<?php

namespace App\Http\Controllers;

use App\Models\ThemePark;
use Illuminate\Http\Request;

class ThemeParkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themeparks = ThemePark::with('activities')->get();
        $hasActiveBooking = auth()->check() && auth()->user()->hasValidHotelBooking();
        return view('theme-parks.index', compact('themeparks', 'hasActiveBooking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ThemePark $themePark)
    {
        $themePark->load('activities');
        return view('theme-parks.show', compact('themePark'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThemePark $themePark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThemePark $themePark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThemePark $themePark)
    {
        //
    }
}
