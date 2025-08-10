<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = \App\Models\SuperAdmin::first();
        
        if ($superAdmin) {
            // Hotel Owner Admin
            \App\Models\Admin::create([
                'name' => 'Hotel Manager',
                'email' => 'hotel@paradiseisland.com',
                'password' => \Illuminate\Support\Facades\Hash::make('hotel123'),
                'business_type' => 'hotel',
                'permissions' => ['manage_hotel_bookings', 'view_hotel_stats', 'cancel_bookings', 'mark_as_paid'],
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]);

            // Ferry Operator Admin
            \App\Models\Admin::create([
                'name' => 'Ferry Operator',
                'email' => 'ferry@paradiseisland.com',
                'password' => \Illuminate\Support\Facades\Hash::make('ferry123'),
                'business_type' => 'ferry',
                'permissions' => ['manage_ferry_bookings', 'view_ferry_stats', 'cancel_bookings', 'mark_as_paid'],
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]);

            // Theme Park Owner Admin
            \App\Models\Admin::create([
                'name' => 'Theme Park Manager',
                'email' => 'themepark@paradiseisland.com',
                'password' => \Illuminate\Support\Facades\Hash::make('park123'),
                'business_type' => 'theme_park',
                'permissions' => ['manage_theme_park_bookings', 'view_theme_park_stats', 'cancel_bookings', 'mark_as_paid'],
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]);

            // Beach Event Organizer Admin
            \App\Models\Admin::create([
                'name' => 'Beach Event Organizer',
                'email' => 'beach@paradiseisland.com',
                'password' => \Illuminate\Support\Facades\Hash::make('beach123'),
                'business_type' => 'beach_event',
                'permissions' => ['manage_beach_event_bookings', 'view_beach_event_stats', 'cancel_bookings', 'mark_as_paid'],
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]);
        }
    }
}
