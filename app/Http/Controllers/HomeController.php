<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\ThemePark;
use App\Models\BeachEvent;
use App\Models\Advertisement;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured content for homepage
        $featuredHotels = Hotel::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $featuredParks = ThemePark::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $upcomingBeachEvents = BeachEvent::where('is_active', true)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->take(6)
            ->get();

        $activeAds = Advertisement::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('priority', 'desc')
            ->get()
            ->groupBy('position');

        return view('home', compact(
            'featuredHotels',
            'featuredParks', 
            'upcomingBeachEvents',
            'activeAds'
        ));
    }

    public function map()
    {
        $hotels = Hotel::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'location', 'latitude', 'longitude']);

        $parks = ThemePark::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'location', 'latitude', 'longitude']);

        $beachEvents = BeachEvent::where('is_active', true)
            ->where('start_time', '>', now())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'location', 'latitude', 'longitude', 'start_time']);

        return view('map', compact('hotels', 'parks', 'beachEvents'));
    }
}
