<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireHotelBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $hasActiveHotelBooking = \App\Models\HotelBooking::where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasActiveHotelBooking) {
            return redirect()->route('hotels.index')
                ->with('error', 'You must have an active hotel booking to access this service. Please book a hotel first.');
        }

        return $next($request);
    }
}
