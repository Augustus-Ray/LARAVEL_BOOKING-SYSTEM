<?php

namespace App\Http\Controllers;

use App\Models\BeachEvent;
use Illuminate\Http\Request;

class BeachEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beachEvents = BeachEvent::orderBy('event_date', 'asc')->get();
        return view('beach-events.index', compact('beachEvents'));
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
    public function show(BeachEvent $beachEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeachEvent $beachEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BeachEvent $beachEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeachEvent $beachEvent)
    {
        //
    }
}
