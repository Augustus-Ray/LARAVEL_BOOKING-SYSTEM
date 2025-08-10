<?php

namespace App\Http\Controllers;

use App\Models\Ferry;
use Illuminate\Http\Request;

class FerryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ferries = Ferry::all();
        return view('ferries.index', compact('ferries'));
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
    public function show(Ferry $ferry)
    {
        $hasValidHotelBooking = auth()->check() && auth()->user()->hasValidHotelBooking();
        return view('ferries.show', compact('ferry', 'hasValidHotelBooking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ferry $ferry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ferry $ferry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ferry $ferry)
    {
        //
    }
}
